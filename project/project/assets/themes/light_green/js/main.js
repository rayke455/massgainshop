// Preloader area
const preloader = document.getElementById("preloader");
const preloaderFunction = () => {
    preloader.style.display = "none";
};

// toggleSideMenu start
const toggleSideMenu = () => {
    document.body.classList.toggle("toggle-sidebar");
};
// toggleSideMenu end

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


$(document).ready(function () {
    // owl carousel dashboard card
    $('.carousel-area1').owlCarousel({
        loop: true,
        autoplay: false,
        margin: 20,
        nav: false,
        dots: true,
        // rtl:true,
        responsive: {
            0: {
                items: 1,
                dotsEach: 3,
            },
            550: {
                items: 2
            },
            991: {
                items: 3
            },
            1200: {
                items: 4
            },
        }
    });
    // plan_area payment_slider
    $('.payment-slider').owlCarousel({
        loop: true,
        autoplay: false,
        autoplayTimeout: 1000,
        margin: 20,
        nav: false,
        dots: false,
        responsive: {
            0: {
                items: 3
            },
            600: {
                items: 6
            },
            1000: {
                items: 8
            }
        }
    });

    // Owl carousel
    $(function (e) {
        "use strict";
        $('.testimonial-carousel').owlCarousel({
            loop: true,
            autoplay: true,
            margin: 20,
            nav: false,
            dots: true,
            // rtl: true,
            responsive: {
                0: {
                    items: 1
                },
                600: {
                    items: 2
                },
                1000: {
                    items: 3
                }
            }
        });
    });

    /*=== cmn select2 ===*/
    $('.cmn-select2').select2();

    // cmn select2 modal start
    $(".modal-select").select2({
        dropdownParent: $("#formModal"),
    });

});


// input file preview
// const previewImage = (id) => {
//     document.getElementById(id).src = URL.createObjectURL(event.target.files[0]);
// };

// Tooltip
const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));


/*=== Counter ===*/
$('.achivement-counter').counterUp({
    delay: 10,
    time: 1000
});





