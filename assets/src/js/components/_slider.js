import Swiper from "swiper/bundle";

class Slider {
  constructor() {
    // this.cardsSliderContainer = document.querySelectorAll(".slider-cards");
    // this.imagesSliderContainer = document.querySelector(".slider-images");

    // if (this.cardsSliderContainer) {
    //   this.initCardsSlider();
    // }
    // if (this.imagesSliderContainer) {
    //   this.initImagesSlider();
    // }
  }
  /*
  initCardsSlider() {
    this.cardsSliderContainer.forEach((slider) => {
      new Swiper(slider, {
        loop: true,
        spaceBetween: 15,
        slidesPerView: 1.2,
        keyboard: {
          enabled: true,
        },
        pagination: {
          el: ".swiper-pagination",
          clickable: true,
          dynamicBullets: true,
        },
        breakpoints: {
          640: {
            slidesPerView: 2,
          },
          1024: {
            slidesPerView: 3,
            spaceBetween: 30,
          },
        },
      });
    });
  }

  initImagesSlider() {
    this.imagesSlider = new Swiper(this.imagesSliderContainer, {
      loop: true,
      spaceBetween: 20,
      slidesPerView: 1.2,
      keyboard: {
        enabled: true,
      },
      autoplay: {
        delay: 2500,
        disableOnInteraction: false,
      },
      speed: 1000,
      pagination: {
        el: ".swiper-pagination",
        clickable: true,
        dynamicBullets: true,
      },
      breakpoints: {
        640: {
          slidesPerView: 2,
        },
        1024: {
          slidesPerView: 3,
        },
      },
    });
  }
  */
}

export default Slider;
