/**
 * MCAL Course — Shared JS
 * Handles: sidebar, progress, quiz, code tabs, copy button, section collapse
 */
(function () {
  'use strict';

  /* ── Progress tracking (localStorage) ────────────────────────── */
  const COURSE_KEY = 'mcal_progress';

  function getProgress() {
    try { return JSON.parse(localStorage.getItem(COURSE_KEY) || '{}'); }
    catch (_) { return {}; }
  }

  function markLesson(lessonId, done) {
    const p = getProgress();
    p[lessonId] = done;
    localStorage.setItem(COURSE_KEY, JSON.stringify(p));
    refreshSidebarStatus();
    refreshProgressBar();
  }

  function isLessonDone(lessonId) {
    return !!getProgress()[lessonId];
  }

  /* ── Sidebar ──────────────────────────────────────────────────── */
  function initSidebar() {
    const sidebar = document.querySelector('.course-sidebar');
    const toggle  = document.querySelector('.sidebar-toggle');

    // Module expand/collapse
    document.querySelectorAll('.sidebar-module-title').forEach(title => {
      title.addEventListener('click', () => {
        title.closest('.sidebar-module').classList.toggle('open');
      });
    });

    // Open the module containing the active lesson
    const activeLesson = document.querySelector('.sidebar-lesson.active');
    if (activeLesson) {
      activeLesson.closest('.sidebar-module')?.classList.add('open');
    } else {
      document.querySelector('.sidebar-module')?.classList.add('open');
    }

    // Mobile toggle
    if (toggle) {
      toggle.addEventListener('click', () => sidebar.classList.toggle('open'));
      document.addEventListener('click', e => {
        if (sidebar.classList.contains('open') &&
            !sidebar.contains(e.target) && e.target !== toggle) {
          sidebar.classList.remove('open');
        }
      });
    }
  }

  function refreshSidebarStatus() {
    document.querySelectorAll('.sidebar-lesson[data-id]').forEach(el => {
      el.classList.toggle('done', isLessonDone(el.dataset.id));
    });
  }

  /* ── Progress bar ─────────────────────────────────────────────── */
  function refreshProgressBar() {
    const total  = document.querySelectorAll('.sidebar-lesson[data-id]').length;
    if (!total) return;
    const done   = Object.values(getProgress()).filter(Boolean).length;
    const bar    = document.querySelector('.progress-bar-fill');
    if (bar) bar.style.width = Math.round((done / total) * 100) + '%';
  }

  /* ── Section collapse ─────────────────────────────────────────── */
  function initSectionCollapse() {
    document.querySelectorAll('.section-head').forEach(head => {
      head.addEventListener('click', () => {
        head.closest('.section-block').classList.toggle('collapsed');
      });
    });
  }

  /* ── Code tabs & copy ─────────────────────────────────────────── */
  function initCode() {
    // Tab switching
    document.querySelectorAll('.code-tabs').forEach(tabBar => {
      tabBar.querySelectorAll('.code-tab').forEach(tab => {
        tab.addEventListener('click', () => {
          const container = tab.closest('.section-body');
          container.querySelectorAll('.code-tab').forEach(t => t.classList.remove('active'));
          container.querySelectorAll('.code-pane').forEach(p => p.classList.remove('active'));
          tab.classList.add('active');
          const target = container.querySelector('.code-pane[data-tab="' + tab.dataset.tab + '"]');
          if (target) target.classList.add('active');
        });
      });
      // Activate first tab
      tabBar.querySelector('.code-tab')?.click();
    });

    // Copy buttons
    document.querySelectorAll('.copy-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        const pane = btn.closest('.code-block-wrap').querySelector('code');
        navigator.clipboard.writeText(pane?.textContent || '').then(() => {
          btn.textContent = 'Copied!';
          btn.classList.add('copied');
          setTimeout(() => { btn.textContent = 'Copy'; btn.classList.remove('copied'); }, 2000);
        });
      });
    });
  }

  /* ── Quiz ─────────────────────────────────────────────────────── */
  function initQuiz() {
    const form = document.querySelector('.quiz-form');
    if (!form) return;

    // Option selection
    form.querySelectorAll('.q-option').forEach(opt => {
      opt.addEventListener('click', () => {
        const group = opt.closest('.q-options');
        group.querySelectorAll('.q-option').forEach(o => o.classList.remove('selected'));
        opt.classList.add('selected');
        opt.querySelector('input').checked = true;
      });
    });

    // Submit
    const submitBtn = form.querySelector('.quiz-submit');
    const resultEl  = form.querySelector('.quiz-result');
    submitBtn?.addEventListener('click', () => {
      let score = 0, total = 0;
      let allAnswered = true;

      form.querySelectorAll('.quiz-question').forEach(q => {
        const selected  = q.querySelector('.q-option.selected');
        const feedback  = q.querySelector('.q-feedback');
        total++;

        if (!selected) { allAnswered = false; return; }

        const isCorrect = selected.dataset.correct === 'true';
        q.querySelectorAll('.q-option').forEach(o => {
          o.classList.remove('correct', 'wrong');
          if (o.dataset.correct === 'true') o.classList.add('correct');
          else if (o === selected && !isCorrect) o.classList.add('wrong');
        });

        if (feedback) {
          feedback.classList.add('show');
          feedback.classList.toggle('ok',  isCorrect);
          feedback.classList.toggle('err', !isCorrect);
        }
        if (isCorrect) score++;
      });

      if (!allAnswered) { alert('Vui lòng trả lời tất cả câu hỏi.'); return; }

      if (resultEl) {
        resultEl.classList.add('show');
        const pass = score >= Math.ceil(total * 0.7);
        resultEl.classList.toggle('pass', pass);
        resultEl.classList.toggle('fail', !pass);
        resultEl.textContent = pass
          ? `✓ Xuất sắc! Bạn đúng ${score}/${total} câu.`
          : `✗ Bạn đúng ${score}/${total} câu. Hãy ôn lại lý thuyết và thử lại.`;
        if (pass) markLesson(document.body.dataset.lessonId, true);
      }
      submitBtn.textContent = 'Làm lại';
      submitBtn.addEventListener('click', () => location.reload(), { once: true });
    });
  }

  /* ── Complete button ──────────────────────────────────────────── */
  function initCompleteBtn() {
    const btn = document.querySelector('.complete-btn');
    if (!btn) return;
    const id = document.body.dataset.lessonId;
    if (!id) return;

    if (isLessonDone(id)) {
      btn.classList.add('done');
      btn.textContent = '✓ Đã hoàn thành';
    }

    btn.addEventListener('click', () => {
      const done = !btn.classList.contains('done');
      markLesson(id, done);
      btn.classList.toggle('done', done);
      btn.textContent = done ? '✓ Đã hoàn thành' : '○ Đánh dấu hoàn thành';
    });
  }

  /* ── Init ─────────────────────────────────────────────────────── */
  function init() {
    initSidebar();
    initSectionCollapse();
    initCode();
    initQuiz();
    initCompleteBtn();
    refreshSidebarStatus();
    refreshProgressBar();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
