import Choices from "choices.js";

class Form_control {
  constructor() {
    this.select = document.querySelectorAll(".select2");
    
    if (this.select) {
      this.initSelect();
    }
  }

  initSelect() {
    this.select.forEach((sel) => {
      new Choices(sel, {
        shouldSort: false,
      });
    });
  }
}

export default Form_control;
