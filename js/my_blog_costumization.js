(function ($) {
    $(window).on('load resize', function desktopViewUpdate() {
        var viewportWidth = $(window).width();
        if (viewportWidth > 1199) {
            $("#right-sidebar").removeClass("col-md-4").addClass("col-md-3");
            $("#primary").removeClass("col-md-8").addClass("col-md-7 offset-md-1");
        }
        else {
            $("#right-sidebar").removeClass("col-md-3").addClass("col-md-4");
            $("#primary").removeClass("col-md-7 offset-md-1").addClass("col-md-8");
        }

        if (viewportWidth < 576) {
            $(".navbar #main-menu > li a").not(".navbar #main-menu > li:last-of-type a").addClass("main-menu-toggle-only");
        }
        else {
            $(".navbar #main-menu > li a").not(".navbar #main-menu > li:last-of-type a").removeClass("main-menu-toggle-only");
        }
    });
})(jQuery);