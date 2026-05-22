(function () {
  'use strict';

  const BASE = '/embeddedio/categories/';
  const SOURCES = [
    { key: 'videos',       label: 'Video',       file: 'videos.json',       fields: ['title','description','tags'],        url: v => v.url,       thumb: v => v.thumbnail_url },
    { key: 'news',         label: 'Tin tức',     file: 'news.json',         fields: ['message','title'],                   url: v => v.permalink_url, thumb: v => v.full_picture },
    { key: 'facebook',     label: 'Bài đăng',   file: 'facebook_posts.json',fields: ['title','description'],              url: v => v.permalink_url, thumb: v => v.full_picture },
    { key: 'recruitments', label: 'Tuyển dụng', file: 'recruitments.json',  fields: ['title','description','tags'],        url: v => v.apply?.email ? null : null, thumb: v => v.full_picture },
    { key: 'events',       label: 'Sự kiện',    file: 'events.json',        fields: ['title','location','categories'],     url: v => v.url,       thumb: null },
    { key: 'blogs',        label: 'Blog',        file: 'blogs.json',         fields: ['title','excerpt','tags'],            url: v => v.url,       thumb: v => v.thumbnail },
  ];

  let cache = {};
  let overlay, input, results, tabs, activeTab = 'all', lastQuery = '', justClosed = false;

  function esc(s) {
    return String(s || '').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
  }

  function highlight(text, q) {
    if (!q) return esc(text);
    const re = new RegExp('(' + q.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + ')', 'gi');
    return esc(text).replace(re, '<mark>$1</mark>');
  }

  function matches(item, fields, q) {
    return fields.some(f => {
      const val = item[f];
      if (!val) return false;
      if (Array.isArray(val)) return val.some(v => String(v).toLowerCase().includes(q));
      return String(val).toLowerCase().includes(q);
    });
  }

  async function fetchSource(src) {
    if (cache[src.key]) return cache[src.key];
    try {
      const res = await fetch(BASE + src.file, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
      cache[src.key] = await res.json();
    } catch (e) {
      cache[src.key] = [];
    }
    return cache[src.key];
  }

  function renderCard(item, src, q) {
    const url   = src.url ? src.url(item) : null;
    const thumb = src.thumb ? src.thumb(item) : null;
    const title = item.title || item.message?.slice(0, 80) || '(Không có tiêu đề)';
    const desc  = item.description || item.excerpt || item.message?.slice(0, 120) || '';
    const tag   = `<span class="sr-badge">${esc(src.label)}</span>`;

    return `<a class="sr-card" href="${esc(url || '#')}" target="${url ? '_blank' : '_self'}" rel="noopener">
      ${thumb ? `<img class="sr-thumb" src="${esc(thumb)}" alt="" loading="lazy" onerror="this.style.display='none'">` : '<div class="sr-thumb sr-thumb--empty"></div>'}
      <div class="sr-card-body">
        ${tag}
        <p class="sr-card-title">${highlight(title, q)}</p>
        ${desc ? `<p class="sr-card-desc">${highlight(desc.slice(0, 100), q)}…</p>` : ''}
      </div>
    </a>`;
  }

  async function runSearch(q) {
    if (!q || q.length < 2) {
      results.innerHTML = '<p class="sr-hint">Nhập ít nhất 2 ký tự…</p>';
      renderTabs({});
      return;
    }
    results.innerHTML = '<p class="sr-hint">Đang tìm…</p>';
    const qLow = q.toLowerCase();

    const all = await Promise.all(SOURCES.map(async src => {
      const data = await fetchSource(src);
      const hits = data.filter(item => matches(item, src.fields, qLow));
      return { src, hits };
    }));

    const byKey = {};
    all.forEach(({ src, hits }) => { byKey[src.key] = { src, hits }; });
    renderTabs(byKey, q);
    renderResults(byKey, q);
  }

  function renderTabs(byKey, q) {
    const total = Object.values(byKey).reduce((s, v) => s + v.hits.length, 0);
    let html = `<button class="sr-tab ${activeTab === 'all' ? 'active' : ''}" data-tab="all">Tất cả <span>${total || ''}</span></button>`;
    SOURCES.forEach(src => {
      const count = byKey[src.key]?.hits.length || 0;
      if (count > 0)
        html += `<button class="sr-tab ${activeTab === src.key ? 'active' : ''}" data-tab="${src.key}">${esc(src.label)} <span>${count}</span></button>`;
    });
    tabs.innerHTML = html;
    tabs.querySelectorAll('.sr-tab').forEach(btn => {
      btn.addEventListener('click', () => {
        activeTab = btn.dataset.tab;
        tabs.querySelectorAll('.sr-tab').forEach(b => b.classList.toggle('active', b === btn));
        if (q) renderResults(byKey, q);
      });
    });
  }

  function renderResults(byKey, q) {
    const toShow = activeTab === 'all'
      ? SOURCES.flatMap(src => (byKey[src.key]?.hits || []).map(item => ({ item, src })))
      : (byKey[activeTab]?.hits || []).map(item => ({ item, src: byKey[activeTab].src }));

    if (!toShow.length) {
      results.innerHTML = `<p class="sr-empty">Không tìm thấy kết quả cho "<strong>${esc(q)}</strong>"</p>`;
      return;
    }
    results.innerHTML = toShow.slice(0, 30).map(({ item, src }) => renderCard(item, src, q)).join('');
  }

  function buildOverlay() {
    const el = document.createElement('div');
    el.id = 'unified-search';
    el.setAttribute('role', 'dialog');
    el.setAttribute('aria-modal', 'true');
    el.setAttribute('aria-label', 'Tìm kiếm');
    el.innerHTML = `
      <div class="sr-backdrop"></div>
      <div class="sr-panel">
        <div class="sr-header">
          <label for="sr-input" class="sr-only">Tìm kiếm</label>
          <input id="sr-input" type="search" autocomplete="off" spellcheck="false"
            placeholder="Tìm video, tin tức, việc làm, sự kiện…" aria-autocomplete="list" aria-controls="sr-results">
          <button class="sr-close" aria-label="Đóng">✕</button>
        </div>
        <div id="sr-tabs" role="tablist"></div>
        <div id="sr-results" role="listbox" aria-live="polite"></div>
      </div>`;
    document.body.appendChild(el);
    return el;
  }

  function open() {
    overlay.removeAttribute('hidden');
    input.focus();
    input.select();
  }

  function close() {
    overlay.setAttribute('hidden', '');
    // If we're on a WP search results page (no Elementor header), go home
    if (document.body.classList.contains('search-results') ||
        document.body.classList.contains('search-no-results')) {
      window.location.href = '/';
      return;
    }
    justClosed = true;
    setTimeout(() => { justClosed = false; }, 300);
  }

  function init() {
    overlay = buildOverlay();
    input   = overlay.querySelector('#sr-input');
    results = overlay.querySelector('#sr-results');
    tabs    = overlay.querySelector('#sr-tabs');
    overlay.setAttribute('hidden', '');

    // Close
    overlay.querySelector('.sr-close').addEventListener('click', close);
    overlay.querySelector('.sr-backdrop').addEventListener('click', close);
    document.addEventListener('keydown', e => { if (e.key === 'Escape') close(); });

    // Keyboard shortcut: / or Ctrl+K
    document.addEventListener('keydown', e => {
      if ((e.key === '/' || (e.ctrlKey && e.key === 'k')) && !['INPUT','TEXTAREA'].includes(document.activeElement.tagName)) {
        e.preventDefault();
        open();
      }
    });

    // Debounced search
    let timer;
    input.addEventListener('input', () => {
      const q = input.value.trim();
      if (q === lastQuery) return;
      lastQuery = q;
      clearTimeout(timer);
      timer = setTimeout(() => runSearch(q), 250);
    });

    // Intercept all search forms (nav-search, WP default search forms)
    document.addEventListener('submit', e => {
      const form = e.target.closest('form');
      if (!form) return;
      const hasS = form.querySelector('[name="s"]');
      const isNavSearch = form.closest('.nav-search');
      if (!hasS && !isNavSearch) return;
      e.preventDefault();
      const val = (hasS?.value || form.querySelector('[type="search"]')?.value || '').trim();
      if (val) { input.value = val; lastQuery = ''; }
      open();
      if (val) runSearch(val);
    });

    // Also trigger on nav search input focus
    document.addEventListener('focus', e => {
      if (justClosed) return;
      if (e.target.closest('.nav-search')) {
        const val = e.target.value?.trim() || '';
        input.value = val;
        open();
      }
    }, true);

    // Handle both /?search= (PHP redirect) and /?s= (direct WP search, JS fallback)
    const params = new URLSearchParams(location.search);
    const urlQ = params.get('search') || params.get('s');
    if (urlQ) {
      history.replaceState(null, '', location.pathname);
      input.value = urlQ;
      lastQuery = '';
      open();
      runSearch(urlQ);
    }
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
