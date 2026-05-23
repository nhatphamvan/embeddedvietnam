/* embedded.io.vn — Child Theme JS (vanilla, no jQuery) */
(function () {
    'use strict';

    // ── Smooth scroll for anchor links ────────────────────────────────────
    document.addEventListener('click', function (e) {
        var link = e.target.closest('a[href*="#"]');
        if (!link) return;

        var href = link.getAttribute('href');
        if (href === '#' || href === '#0') return;

        var hash = href.indexOf('#') !== -1 ? href.slice(href.indexOf('#')) : null;
        if (!hash) return;

        var target = document.querySelector(hash);
        if (!target) return;

        e.preventDefault();
        var top = target.getBoundingClientRect().top + window.scrollY - 80;
        window.scrollTo({ top: top, behavior: 'smooth' });
    });

    // ── Sticky header class on scroll ────────────────────────────────────
    var header = document.getElementById('site-header');
    if (header) {
        window.addEventListener('scroll', function () {
            header.classList.toggle('scrolled', window.scrollY > 50);
        }, { passive: true });
    }

    // ── Reading progress bar ──────────────────────────────────────────────
    var bar = document.getElementById('reading-progress-bar');
    if (bar) {
        window.addEventListener('scroll', function () {
            var docHeight = document.documentElement.scrollHeight - window.innerHeight;
            var progress  = docHeight > 0 ? (window.scrollY / docHeight) * 100 : 0;
            bar.style.width = progress + '%';
        }, { passive: true });
    }

    // ── Inject Courses section above Recruitment on homepage ─────────────
    if (document.body.classList.contains('home')) {
        document.addEventListener('DOMContentLoaded', function () {
            // Find the "Tuyển dụng" heading anywhere on the page
            var recruitHeading = null;
            document.querySelectorAll('h2').forEach(function (el) {
                if (el.textContent.trim().indexOf('Tuyển dụng') !== -1) {
                    recruitHeading = el;
                }
            });
            if (!recruitHeading) return;

            // Walk up to find the Elementor section wrapper
            var anchor = recruitHeading;
            while (anchor && !anchor.classList.contains('elementor-section') &&
                   !anchor.classList.contains('e-con') &&
                   anchor.parentElement) {
                anchor = anchor.parentElement;
            }
            if (!anchor || anchor === document.body) return;

            var section = document.createElement('div');
            section.className = 'eio-courses-section';
            section.innerHTML =
                '<div class="wrap container-aligned">' +
                  '<div class="eio-section__header">' +
                    '<h2>Khóa học</h2>' +
                    '<a class="see-all" href="/mcal/">Xem tất cả →</a>' +
                  '</div>' +
                  '<div class="eio-courses-grid">' +

                    '<a class="eio-course-card" href="/mcal/modules/01-mcu/rcc-stm32f411.html">' +
                      '<div class="eio-course-card__header">' +
                        '<span class="eio-course-card__icon">⏱️</span>' +
                        '<div class="eio-course-card__header-text">' +
                          '<div class="eio-course-card__badge">Module 1 — MCU</div>' +
                          '<div class="eio-course-card__header-title">RCC — STM32F411xC/E</div>' +
                        '</div>' +
                      '</div>' +
                      '<div class="eio-course-card__body">' +
                        '<p class="eio-course-card__desc">Reset and Clock Control — PLL, bus prescalers, clock tree cho STM32F411.</p>' +
                        '<div class="eio-course-card__tags">' +
                          '<span class="eio-course-tag eio-course-tag--theory">Lý thuyết</span>' +
                          '<span class="eio-course-tag eio-course-tag--code">Code</span>' +
                          '<span class="eio-course-tag eio-course-tag--autosar">AUTOSAR</span>' +
                        '</div>' +
                      '</div>' +
                      '<div class="eio-course-card__footer">' +
                        '<span>Bài 1 / Module 1</span>' +
                        '<span class="eio-course-card__cta">Học ngay →</span>' +
                      '</div>' +
                    '</a>' +

                    '<a class="eio-course-card" href="/mcal/modules/01-mcu/mcu-driver-mcal.html">' +
                      '<div class="eio-course-card__header">' +
                        '<span class="eio-course-card__icon">🔧</span>' +
                        '<div class="eio-course-card__header-text">' +
                          '<div class="eio-course-card__badge">Module 1 — MCU</div>' +
                          '<div class="eio-course-card__header-title">MCU Driver MCAL</div>' +
                        '</div>' +
                      '</div>' +
                      '<div class="eio-course-card__body">' +
                        '<p class="eio-course-card__desc">MCU Driver MCAL theo AUTOSAR CP R25-11 cho STM32F4 &amp; STM32F1.</p>' +
                        '<div class="eio-course-card__tags">' +
                          '<span class="eio-course-tag eio-course-tag--theory">Lý thuyết</span>' +
                          '<span class="eio-course-tag eio-course-tag--code">Code</span>' +
                          '<span class="eio-course-tag eio-course-tag--autosar">AUTOSAR</span>' +
                        '</div>' +
                      '</div>' +
                      '<div class="eio-course-card__footer">' +
                        '<span>Bài 2 / Module 1</span>' +
                        '<span class="eio-course-card__cta">Học ngay →</span>' +
                      '</div>' +
                    '</a>' +

                    '<a class="eio-course-card eio-course-card--all" href="/mcal/">' +
                      '<span class="all-icon">📚</span>' +
                      '<span class="all-label">Xem tất cả khóa học</span>' +
                      '<span class="all-sub">8 bài · 3 module · Miễn phí</span>' +
                    '</a>' +

                  '</div>' +
                '</div>';

            anchor.parentElement.insertBefore(section, anchor);
        });
    }

})();
