import "./_gallery.scss";
import Swiper from "swiper/bundle";

document.addEventListener('DOMContentLoaded', function () {
  const mySwiper = new Swiper('.mySwiper', {
    loop: true,
    slidesPerView: 3,
    spaceBetween: 20, 
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    },
    pagination: {
      el: '.swiper-pagination',
      clickable: true,
    },
    breakpoints: {
      0: {
        slidesPerView: 1,
      },
      768: {
        slidesPerView: 2,
      },
      992: {
        slidesPerView: 3,
      }
    }
  });
});
  