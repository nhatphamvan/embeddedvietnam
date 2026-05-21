(function ($) {
    'use strict';

    // Smooth scroll for anchor links
    $('a[href*="#"]').not('[href="#"]').on('click', function (e) {
        var target = $(this.hash);
        if (!target.length) return;
        e.preventDefault();
        $('html, body').animate({ scrollTop: target.offset().top - 80 }, 500);
    });

    // Active nav highlight on scroll
    $(window).on('scroll', function () {
        $('#site-header').toggleClass('scrolled', $(this).scrollTop() > 50);
    });

})(jQuery);
