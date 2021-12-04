$(document).ready(function() {
    window.onscroll = function() {myFunction()};

    // Get the header
    var header = document.getElementById("main-header");

    // Get the offset position of the navbar
    var sticky = header.offsetTop;

    // Add the sticky class to the header when you reach its scroll position. Remove "sticky" when you leave the scroll position
    function myFunction() {
        if (window.pageYOffset > sticky) {
            header.classList.add("sticky");
        } else {
            header.classList.remove("sticky");
        }
    }

    //Easy Scroll
    $("#start-scroll").click(function(e) {
        e.preventDefault();
        $('html, body').animate({
            scrollTop: $("#start").offset().top
        }, 1500);
    });
    $("#fill-form").click(function(e) {
        e.preventDefault();
        $('html, body').animate({
            scrollTop: $("#locate_form").offset().top
        }, 2000);
    });
});

