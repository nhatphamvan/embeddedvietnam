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

    // ── Header nav: inject "Khóa học" menu item ───────────────────────────
    function injectHeaderNav() {
        /* OceanWP primary nav or Elementor nav-menu widget */
        var nav = document.querySelector('#primary-menu, .elementor-nav-menu--main');
        if (!nav || nav.dataset.courseInjected) return;
        nav.dataset.courseInjected = '1';

        var li = document.createElement('li');
        li.className = 'menu-item menu-item-has-children';
        li.innerHTML =
            '<a href="/mcal/">Khóa học</a>' +
            '<ul class="sub-menu">' +
              '<li class="menu-item"><a href="/mcal/">Tất cả khóa học</a></li>' +
              '<li class="menu-item"><a href="/mcal/modules/01-mcu/rcc-stm32f411.html">MCAL — RCC STM32F411</a></li>' +
              '<li class="menu-item"><a href="/mcal/modules/01-mcu/mcu-driver-mcal.html">MCAL — MCU Driver</a></li>' +
            '</ul>';
        nav.appendChild(li);
    }

    // ── Homepage: inject Courses section above Recruitment ────────────────
    function injectCoursesSection() {
        if (!document.body.classList.contains('home')) return;
        if (document.querySelector('.eio-courses-section')) return; /* already injected */

        /* Find heading "Tuyển dụng" in any tag */
        var recruitHeading = null;
        var headings = document.querySelectorAll('h2, h3, .elementor-heading-title');
        for (var i = 0; i < headings.length; i++) {
            if (headings[i].textContent.trim().indexOf('Tuyển dụng') !== -1) {
                recruitHeading = headings[i];
                break;
            }
        }
        if (!recruitHeading) return;

        /* Walk up to the nearest Elementor section/container ancestor */
        var anchor = recruitHeading.parentElement;
        while (anchor && anchor !== document.body) {
            var cls = anchor.className || '';
            if (cls.indexOf('elementor-section') !== -1 ||
                cls.indexOf('elementor-top-section') !== -1 ||
                (cls.indexOf('e-con') !== -1 && anchor.parentElement === anchor.parentElement.parentElement)) {
                break;
            }
            anchor = anchor.parentElement;
        }
        /* Fallback: use the heading's closest section-level ancestor */
        if (!anchor || anchor === document.body) {
            anchor = recruitHeading.closest('section, .elementor-section, .e-con') || recruitHeading.parentElement;
            while (anchor && anchor.parentElement && anchor.parentElement !== document.body) {
                anchor = anchor.parentElement;
            }
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
                      '<span class="eio-course-tag eio-course-tag--theory">L\xfd thuyết</span>' +
                      '<span class="eio-course-tag eio-course-tag--code">Code</span>' +
                      '<span class="eio-course-tag eio-course-tag--autosar">AUTOSAR</span>' +
                    '</div>' +
                  '</div>' +
                  '<div class="eio-course-card__footer">' +
                    '<span>B\xe0i 1 / Module 1</span>' +
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
                      '<span class="eio-course-tag eio-course-tag--theory">L\xfd thuyết</span>' +
                      '<span class="eio-course-tag eio-course-tag--code">Code</span>' +
                      '<span class="eio-course-tag eio-course-tag--autosar">AUTOSAR</span>' +
                    '</div>' +
                  '</div>' +
                  '<div class="eio-course-card__footer">' +
                    '<span>B\xe0i 2 / Module 1</span>' +
                    '<span class="eio-course-card__cta">Học ngay →</span>' +
                  '</div>' +
                '</a>' +

                '<a class="eio-course-card eio-course-card--all" href="/mcal/">' +
                  '<span class="all-icon">📚</span>' +
                  '<span class="all-label">Xem tất cả kh\xf3a học</span>' +
                  '<span class="all-sub">8 b\xe0i \xb7 3 module \xb7 Miễn ph\xed</span>' +
                '</a>' +

              '</div>' +
            '</div>';

        anchor.parentElement.insertBefore(section, anchor);
    }

    /* Run on DOMContentLoaded — but if DOM already loaded (script in footer), run now */
    function onReady(fn) {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', fn);
        } else {
            fn();
        }
    }

    onReady(function () {
        injectHeaderNav();
        injectCoursesSection();
    });

})();
