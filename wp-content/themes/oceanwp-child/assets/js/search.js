(function () {
  'use strict';

  const BASE = '/embeddedio/categories/';
  const SOURCES = [
    { key: 'videos',       label: 'Video',       file: 'videos.json',        fields: ['title','description','tags'],     url: v => v.url,           thumb: v => v.thumbnail_url },
    { key: 'news',         label: 'Tin tức',     file: 'news.json',          fields: ['message','title'],               url: v => v.permalink_url, thumb: v => v.full_picture },
    { key: 'facebook',     label: 'Bài đăng',   file: 'facebook_posts.json', fields: ['title','description'],           url: v => v.permalink_url, thumb: v => v.full_picture },
    { key: 'recruitments', label: 'Tuyển dụng', file: 'recruitments.json',   fields: ['title','description','tags'],    url: v => v.permalink_url, thumb: v => v.full_picture },
    { key: 'events',       label: 'Sự kiện',    file: 'events.json',         fields: ['title','location','categories'], url: v => v.url,           thumb: null },
    { key: 'blogs',        label: 'Blog',        file: 'blogs.json',          fields: ['title','excerpt','tags'],        url: v => v.url,           thumb: v => v.thumbnail },
  ];

  let cache = {};
  let overlay, input, results, tabs;
  let activeTab = 'all';
  let lastQuery = '';
  let isOpen = false;

  /* ── helpers ───────────────────────────────────────────────────────────── */

  function esc(s) {
    return String(s || '').replace(/[&<>"']/g, c =>
      ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
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

  /* ── data ──────────────────────────────────────────────────────────────── */

  async function fetchSource(src) {
    if (cache[src.key]) return cache[src.key];
    try {
      const res = await fetch(BASE + src.file, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
      cache[src.key] = await res.json();
    } catch (_) {
      cache[src.key] = [];
    }
    return cache[src.key];
  }

  /* ── render ────────────────────────────────────────────────────────────── */

  function renderCard(item, src, q) {
    const url   = src.url ? src.url(item) : null;
    const thumb = src.thumb ? src.thumb(item) : null;
    const title = item.title || item.message?.slice(0, 80) || '(Không có tiêu đề)';
    const desc  = item.description || item.excerpt || item.message?.slice(0, 120) || '';

    return `<a class="sr-card" href="${esc(url || '#')}" target="${url ? '_blank' : '_self'}" rel="noopener">
      ${thumb
        ? `<img class="sr-thumb" src="${esc(thumb)}" alt="" loading="lazy" onerror="this.style.display='none'">`
        : '<div class="sr-thumb sr-thumb--empty"></div>'}
      <div class="sr-card-body">
        <span class="sr-badge">${esc(src.label)}</span>
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

    const all = await Promise.all(
      SOURCES.map(async src => ({ src, hits: (await fetchSource(src)).filter(item => matches(item, src.fields, qLow)) }))
    );

    const byKey = Object.fromEntries(all.map(({ src, hits }) => [src.key, { src, hits }]));
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

  /* ── overlay DOM ───────────────────────────────────────────────────────── */

  function buildOverlay() {
    const el = document.createElement('div');
    el.id = 'unified-search';
    el.setAttribute('role', 'dialog');
    el.setAttribute('aria-modal', 'true');
    el.setAttribute('aria-label', 'Tìm kiếm');
    el.hidden = true;
    el.innerHTML = `
      <div class="sr-backdrop"></div>
      <div class="sr-panel">
        <div class="sr-header">
          <label for="sr-input" class="sr-only">Tìm kiếm</label>
          <input id="sr-input" type="search" autocomplete="off" spellcheck="false"
            placeholder="Tìm video, tin tức, việc làm, sự kiện…"
            aria-autocomplete="list" aria-controls="sr-results">
          <button class="sr-close" aria-label="Đóng">✕</button>
        </div>
        <div id="sr-tabs" role="tablist"></div>
        <div id="sr-results" role="listbox" aria-live="polite"></div>
      </div>`;
    document.body.appendChild(el);
    return el;
  }

  /* ── open / close ──────────────────────────────────────────────────────── */

  function open(prefill) {
    if (isOpen) return;
    isOpen = true;
    overlay.hidden = false;
    if (prefill != null && prefill !== input.value) {
      input.value = prefill;
      lastQuery = '';
    }
    // rAF: let the browser paint the overlay before focusing,
    // preventing synchronous focus events from leaking to the page below
    requestAnimationFrame(() => {
      input.focus();
      if (input.value.trim().length >= 2) runSearch(input.value.trim());
    });
  }

  function close() {
    if (!isOpen) return;
    isOpen = false;
    overlay.hidden = true;
    // If PHP redirect failed and user is on the WP search page (no header), go home
    if (document.body.classList.contains('search-results') ||
        document.body.classList.contains('search-no-results')) {
      window.location.href = '/';
    }
  }

  /* ── init ──────────────────────────────────────────────────────────────── */

  function init() {
    overlay = buildOverlay();
    input   = overlay.querySelector('#sr-input');
    results = overlay.querySelector('#sr-results');
    tabs    = overlay.querySelector('#sr-tabs');

    // Close: X button, backdrop click, Escape key
    overlay.querySelector('.sr-close').addEventListener('click', close);
    overlay.querySelector('.sr-backdrop').addEventListener('click', close);
    document.addEventListener('keydown', e => { if (e.key === 'Escape') close(); });

    // Open: keyboard shortcut (/ or Ctrl+K)
    document.addEventListener('keydown', e => {
      if (isOpen) return;
      if ((e.key === '/' || (e.ctrlKey && e.key === 'k')) &&
          !['INPUT', 'TEXTAREA'].includes(document.activeElement?.tagName)) {
        e.preventDefault();
        open();
      }
    });

    // Debounced search on typing
    let timer;
    input.addEventListener('input', () => {
      const q = input.value.trim();
      if (q === lastQuery) return;
      lastQuery = q;
      clearTimeout(timer);
      timer = setTimeout(() => runSearch(q), 250);
    });

    // Intercept native WP search form submissions
    document.addEventListener('submit', e => {
      const form = e.target.closest('form');
      if (!form) return;
      if (!form.querySelector('[name="s"]') && !form.closest('.nav-search')) return;
      e.preventDefault();
      const val = (form.querySelector('[name="s"]')?.value || '').trim();
      open(val || undefined);
    });

    // Open on explicit click inside nav-search area
    // Using 'click' (not 'focus') — only fires on real user interaction,
    // never when the browser auto-returns focus after overlay closes
    document.addEventListener('click', e => {
      if (isOpen) return;
      if (!e.target.closest('.nav-search')) return;
      if (e.target.matches('[type="submit"], button[type="submit"]')) return;
      e.preventDefault();
      open();
    });

    // JS fallback: handle /?s= (if PHP redirect missed) and /?search= (after redirect)
    const params = new URLSearchParams(location.search);
    const urlQ = params.get('s') || params.get('search');
    if (urlQ) {
      history.replaceState(null, '', location.pathname);
      open(urlQ);
    }
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
