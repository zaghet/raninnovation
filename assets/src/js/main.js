import * as bootstrap from "bootstrap";
import General from "./components/_general";
import Menu from "./components/_menu";
import Slider from "./components/_slider";
import Animate from "./components/_animate";
import Form from "./components/_form";

const App = {
  /**
   * App.init
   */
  init() {
    // General scripts
    function initGeneral() {
      return new General();
    }
    initGeneral();

    // Menu scripts
    function initMenu() {
      return new Menu();
    }
    initMenu();

    // Slider scripts
    function initSlider() {
      return new Slider();
    }
    initSlider();

    // Animate scripts
    function initAnimate() {
      return new Animate();
    }
    initAnimate();

    // Form scripts
    function initForm() {
      return new Form();
    }
    initForm();

  },
};

document.addEventListener("DOMContentLoaded", () => {
  App.init();
});
