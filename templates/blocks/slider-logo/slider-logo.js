import "./_slider-logo.scss";
import Swiper from "swiper/bundle";
import "swiper/css/bundle";

document.addEventListener("DOMContentLoaded", function () {
  const mySwiper = new Swiper(".image-slider", {
    loop: false,
    slidesPerView: 4,
    spaceBetween: 20,
    // autoplay: {
    //   delay: 3000, // 3 secondi
    //   disableOnInteraction: false,
    // },
    // navigation: {
    //   nextEl: ".swiper-button-next",
    //   prevEl: ".swiper-button-prev",
    // },
    pagination: {
      el: ".swiper-pagination",
      clickable: true,
    },
    breakpoints: {
      0: {
        slidesPerView: 1,
      },
      576: {
        slidesPerView: 2,
      },
      992: {
        slidesPerView: 3,
      },
      1200: {
        slidesPerView: 4,
      },
    },
  });
});
