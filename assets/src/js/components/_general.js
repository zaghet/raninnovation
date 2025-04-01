import { createPopper } from "@popperjs/core";
import { devLog } from "../utils/_log";

class General {
  constructor() {
    // this.iframe();

    this.popper();
  }

  popper() {
    try {
      const dropdown = createPopper(referenceElement, popperElement, {
        placement: placement || "bottom", // Fallback per placement
      });
    } catch (error) {
      devLog("Errore nella creazione del Popper");
    }
  }

  iframe() {
    const frame = document.getElementById("Iframe");

    if (frame) {
      console.log(frame);

      const adjustIframe = () => {
        try {
          // Controlla se l'iframe è accessibile
          const doc = frame.contentWindow.document;
          frame.style.height = doc.documentElement.scrollHeight + "px";
          frame.style.width = doc.documentElement.scrollWidth + "px";
          console.log("iframe adjusted");
        } catch (e) {
          console.error("Cross-origin issue or iframe not ready", e);
        }
      };

      frame.addEventListener("load", adjustIframe);

      // Polling per verificare quando l'iframe è pronto
      const interval = setInterval(() => {
        if (
          frame.contentWindow &&
          frame.contentWindow.document.readyState === "complete"
        ) {
          clearInterval(interval);
          adjustIframe();
        }
      }, 100);
    }
  }

  fixedNavbar() {
    const navbar = document.getElementById("bottom_header");
    // const content = document.getElementById("masthead");

    if (window.scrollY > 30) {
      navbar.classList.add("fixed-top");
      console.log("fixed-top");

      // add padding top to show content behind navbar
      // const navbar_height = navbar.offsetHeight;
      // content.style.paddingTop = navbar_height + "px";
    } else {
      console.log("remove fixed-top");
      navbar.classList.remove("fixed-top");
      // remove padding top from body
      // content.style.paddingTop = "0";
    }
  }
}

export default General;
