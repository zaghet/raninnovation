import Swiper from "swiper/bundle";
import "./_hero-slider.scss";

document.addEventListener("DOMContentLoaded", () => {
  const heroSliderContainer = document.querySelectorAll(".hero-slider");
  heroSliderContainer.forEach((slider) => {
    new Swiper(slider, {
      loop: true,
      spaceBetween: 15,
      slidesPerView: 1,
      keyboard: {
        enabled: true,
      },
      pagination: {
        el: ".swiper-pagination",
        clickable: true,
        dynamicBullets: true,
      },
    });
  });
});
