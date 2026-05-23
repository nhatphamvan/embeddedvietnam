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

    function onLoad(fn) {
        if (document.readyState === 'complete') { fn(); }
        else { window.addEventListener('load', fn); }
    }

    onLoad(function () { injectHeaderNav(); });

})();
