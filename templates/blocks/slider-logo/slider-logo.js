import "./_slider-logo.scss";

import Swiper from "swiper";
import "swiper/swiper-bundle.css";

// document.addEventListener("DOMContentLoaded", function () {
//     const sliders = document.querySelectorAll(".slider-logo");

//     sliders.forEach(slider => {
//         const swiperInstance = new Swiper(slider, {
//             slidesPerView: 2, // Mostra 2 loghi per viewport
//             spaceBetween: 30, // Spazio tra le slide
//             loop: true, // Loop infinito
//             loopAdditionalSlides: 2,
//             centeredSlides: true,
//             grabCursor: true, // Cambia il cursore per il drag
//             pagination: {
//                 el: slider.querySelector(".swiper-pagination"),
//                 clickable: true,
//                 type: "bullets", // Usa i bullet per la paginazione
//                 renderBullet: function (index, className) {
//                     const totalPages = Math.ceil(this.slides.length / this.params.slidesPerView);
//                     if (index < totalPages) {
//                         return `<span class="${className}"></span>`;
//                     }
//                     return "";
//                 }
//             },
//             breakpoints: {
//                 1024: { slidesPerView: 2, spaceBetween: 20 },
//                 768: { slidesPerView: 2, spaceBetween: 20 },
//                 600: { slidesPerView: 2, spaceBetween: 20 },
//                 400: { slidesPerView: 1, spaceBetween: 20 },
//             },
//             on: {
//                 init: function () {
//                     updatePaginationBullets(this);
//                 },
//                 resize: function () {
//                     updatePaginationBullets(this);
//                 },
//                 slideChange: function () {
//                     updatePaginationBullets(this);
//                 }
//             }
//         });

//         function updatePaginationBullets(swiperInstance) {
//             const totalPages = Math.ceil(swiperInstance.slides.length / swiperInstance.params.slidesPerView);
//             const bullets = slider.querySelectorAll(".swiper-pagination-bullet");

//             bullets.forEach((bullet, index) => {
//                 if (index < totalPages) {
//                     bullet.style.display = "inline-block";
//                 } else {
//                     bullet.style.display = "none";
//                 }
//             });
//         }
//     });
// });

document.addEventListener("DOMContentLoaded", function () {
  const sliders = document.querySelectorAll('.logo-slider.swiper');

  sliders.forEach((slider) => {
    const images = slider.querySelectorAll('img');
    let loaded = 0;

    // Attendi caricamento immagini per evitare glitch su loop
    images.forEach((img) => {
      if (img.complete) {
        loaded++;
      } else {
        img.addEventListener("load", () => {
          loaded++;
          if (loaded === images.length) initSlider(slider);
        });
      }
    });

    if (loaded === images.length) {
      initSlider(slider);
    }
  });

  function initSlider(slider) {
    new Swiper(slider, {
      slidesPerView: 3,
      spaceBetween: 30,
      loop: true,
      autoplay: {
        delay: 2000,
        disableOnInteraction: false,
      },
      preloadImages: true,
      watchSlidesVisibility: true,
      watchSlidesProgress: true,
      pagination: {
        el: slider.querySelector('.swiper-pagination'),
        clickable: true,
      },
      navigation: {
        nextEl: slider.querySelector('.swiper-button-next'),
        prevEl: slider.querySelector('.swiper-button-prev'),
      },
      breakpoints: {
        400: { slidesPerView: 1 },
        600: { slidesPerView: 2 },
        768: { slidesPerView: 3 },
        1024: { slidesPerView: 4 }
      },
      on: {
        init: function () {
          this.update(); // forza aggiornamento dimensioni
        },
      },
    });
  }
});
