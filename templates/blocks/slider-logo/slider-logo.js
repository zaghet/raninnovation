import "./_slider-logo.scss";
import Swiper from "swiper/bundle";
import "swiper/css/bundle";

const slider = document.querySelector(".image-slider");

if (slider) {
  const observer = new IntersectionObserver((entries, obs) => {
    if (entries[0].isIntersecting) {
      new Swiper(slider, {
        loop: false,
        slidesPerView: 4,
        spaceBetween: 20,
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
      obs.unobserve(slider); // evita inizializzazioni multiple
    }
  }, {
    rootMargin: "0px 0px 200px 0px", // inizializza un po' prima che entri in viewport
  });

  observer.observe(slider);
}
