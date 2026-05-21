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

    // Reading progress bar
    var $bar = $('#reading-progress-bar');
    if ( $bar.length ) {
        $(window).on('scroll', function () {
            var scrollTop  = $(this).scrollTop();
            var docHeight  = $(document).height() - $(this).height();
            var progress   = docHeight > 0 ? ( scrollTop / docHeight ) * 100 : 0;
            $bar.css('width', progress + '%');
        });
    }

})(jQuery);
