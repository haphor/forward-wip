$(document).ready(function() {
    /*targeting the footer ul and adding a class*/
    $("footer .footer-widget-content").addClass("footer-toggle");

    /* Check width on page load*/
    if ($(window).width() < 768) {
        /*targeting the footer toggling element and adding a class when screen size less than 768*/
        $("footer .footer-widget h3").addClass("footer-btn");
    } else {}
});

$(window).resize(function() {
    /*If browser resized, check width again */
    if ($(window).width() < 768) {
        /*targeting the footer toggling element and adding a class when screen size less than 768*/
        $("footer .footer-widget h3").addClass("footer-btn");
    } else {
        $("footer .footer-widget h3").removeClass("footer-btn");
    }
});

$(document).ready(function() {
    /*footer accordion fucntion*/
    $(".footer-btn").click(function() {
        $(this)
            .parent()
            .find(".footer-widget-content")
            .slideToggle(); //this depends on the relation between toggler and the footer ul
        $(this).toggleClass("turn");
    });
});
$(document).ready(function() {
    $(document).delegate(".open", "click", function(event) {
        $(this).addClass("oppenned");
        event.stopPropagation();
    });
    $(document).delegate("body", "click", function(event) {
        $(".open").removeClass("oppenned");
    });
    $(document).delegate(".cls", "click", function(event) {
        $(".open").removeClass("oppenned");
        event.stopPropagation();
    });
});