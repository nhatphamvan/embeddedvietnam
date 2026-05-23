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


})();
