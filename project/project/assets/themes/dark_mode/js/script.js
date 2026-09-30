/*
const preloader = document.getElementById("preloader");
window.addEventListener("load", () => {
    setTimeout(() => {
        preloader.style.cssText = `opacity: 0; visibility: hidden;`;
    }, 1000);
});
 */

// add bg to nav
window.addEventListener("scroll", function () {
    let scrollpos = window.scrollY;
    const header = document.querySelector("nav");
    const headerHeight = header.offsetHeight;

    if (scrollpos >= headerHeight) {
        header.classList.add("active");
    } else {
        header.classList.remove("active");
    }
});

// active nav item
const navItem = document.getElementsByClassName("nav-link");
for (const element of navItem) {
    element.addEventListener("click", () => {
        for (const ele of navItem) {
            ele.classList.remove("active");
        }
        element.classList.add("active");
    });
}

$(document).ready(function () {
    $(".testimonials").owlCarousel({
        loop: true,
        margin: 25,
        nav: false,
        dots: true,
        autoplay: true,
        //   rtl: false,
        autoplayTimeout: 3000,
        responsive: {
            0: {
                items: 1,
            },
            768: {
                items: 2,
            },
            992: {
                items: 3,
            },
        },
    });
    $(".gateways").owlCarousel({
        loop: true,
        margin: 25,
        nav: false,
        dots: true,
        autoplay: true,
        rtl: false,
        autoplayTimeout: 3000,
        responsive: {
            0: {
                items: 3,
            },
            768: {
                items: 5,
            },
            992: {
                items: 8,
            },
        },
    });

    // AOS ANIMATION
    AOS.init();

    // COUNTER UP
    $(".counter").counterUp({
        delay: 10,
        time: 3000,
    });

    // SCROLL TOP
    $(document).ready(function () {
        $(".scroll-up").hide();

        $(window).scroll(function () {
            if ($(this).scrollTop() > 100) {
                $(".scroll-up").fadeIn();
            } else {
                $(".scroll-up").fadeOut();
            }
        });

        $(".scroll-up").click(function () {
            $("html, body").animate({scrollTop: 0}, 600);
            return false;
        });
    });
});
const previewImage = (id) => {
    document.getElementById(id).src = URL.createObjectURL(event.target.files[0]);
};
