
/* 
 * Toggles search on and off
 */
(function ($) {
    $(".search-toggle").click(function () {
        $("#search-container").slideToggle('fast', function () {
            $('.search-toggle').toggleClass('active');
        });
        // Optional return false to avoid the page "jumping" when clicked
        return false;
    });
})(jQuery);