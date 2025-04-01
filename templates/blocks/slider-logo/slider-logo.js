import "./_slider-logo.scss";

import Swiper from "swiper";
import "swiper/swiper-bundle.css";

document.addEventListener("DOMContentLoaded", function () {
    const sliders = document.querySelectorAll(".slider-logo");

    sliders.forEach(slider => {
        new Swiper(slider, {
            slidesPerView: 4, // Mostra 4 loghi per viewport
            spaceBetween: 30, // Spazio tra le slide
            loop: true, // Loop infinito
            grabCursor: true, // Cambia il cursore per il drag
            // autoplay: {
            //     delay: 3000, // Cambia logo ogni 3s
            //     disableOnInteraction: false, // Continua l'autoplay anche dopo interazione
            // },
            // navigation: {
            //     nextEl: slider.querySelector(".swiper-button-next"),
            //     prevEl: slider.querySelector(".swiper-button-prev"),
            // },
            pagination: {
                el: slider.querySelector(".swiper-pagination"),
                clickable: true,
            },
            breakpoints: {
                1024: { slidesPerView: 4, spaceBetween: 20 },
                768: { slidesPerView: 3, spaceBetween: 20 },
                600: { slidesPerView: 2, spaceBetween: 20 },
                400: { slidesPerView: 1, spaceBetween: 20 },
            },
        });
    });
});