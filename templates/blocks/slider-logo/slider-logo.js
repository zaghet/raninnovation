import "./_slider-logo.scss";

import Swiper from "swiper";
import "swiper/swiper-bundle.css";

document.addEventListener("DOMContentLoaded", function () {
    const sliders = document.querySelectorAll(".slider-logo");

    sliders.forEach(slider => {
        const swiperInstance = new Swiper(slider, {
            slidesPerView: 4, // Mostra 4 loghi per viewport
            spaceBetween: 30, // Spazio tra le slide
            loop: true, // Loop infinito
            grabCursor: true, // Cambia il cursore per il drag
            pagination: {
                el: slider.querySelector(".swiper-pagination"),
                clickable: true,
                type: "bullets", // Usa i bullet per la paginazione
                renderBullet: function (index, className) {
                    const totalPages = Math.ceil(this.slides.length / this.params.slidesPerView);
                    if (index < totalPages) {
                        return `<span class="${className}"></span>`;
                    }
                    return "";
                }
            },
            breakpoints: {
                1024: { slidesPerView: 4, spaceBetween: 20 },
                768: { slidesPerView: 3, spaceBetween: 20 },
                600: { slidesPerView: 2, spaceBetween: 20 },
                400: { slidesPerView: 1, spaceBetween: 20 },
            },
            on: {
                init: function () {
                    updatePaginationBullets(this);
                },
                resize: function () {
                    updatePaginationBullets(this);
                },
                slideChange: function () {
                    updatePaginationBullets(this);
                }
            }
        });

        function updatePaginationBullets(swiperInstance) {
            const totalPages = Math.ceil(swiperInstance.slides.length / swiperInstance.params.slidesPerView);
            const bullets = slider.querySelectorAll(".swiper-pagination-bullet");

            bullets.forEach((bullet, index) => {
                if (index < totalPages) {
                    bullet.style.display = "inline-block";
                } else {
                    bullet.style.display = "none";
                }
            });
        }
    });
});
