import { devLog } from "../utils/_log";
import LocomotiveScroll from "locomotive-scroll";

class Animate {
  constructor() {
    this.scroll();
    this.locoScroll = null;
  }

  scroll() {
    const page = document.querySelector("#page");
    if (!page || !window.siteVars.locomotive_scroll) {
      return;
    }

    try {
      // Inizializzazione di LocomotiveScroll
      const smoothScroller = new LocomotiveScroll({
        el: page,
        smooth: false,
        multiplier: 0.8,
        tablet: {
          smooth: false,
          horizontalGesture: 0,
        },
        smartphone: {
          smooth: false,
          horizontalGesture: 0,
        },
      });

      // registrazione var di classe con istanza scroller
      this.locoScroll = smoothScroller;

      smoothScroller.on("call", (func) => {
        devLog(func);
        // this[func](); // Per eventuali chiamate dinamiche
      });

      // Trigger degli eventi di WooCommerce
      [
        "woocommerce_cart_loaded_from_session",
        "woocommerce_cart_updated",
        "woocommerce_checkout_init",
        "focus",
      ].forEach((event) => {
        document.addEventListener(event, () => {
          smoothScroller.update();
        });
      });

      // Seleziona il contenitore genitore che già esiste nel DOM
      const container = document.body; // Puoi specificare un contenitore più ristretto se noto

      if (container) {
        const observer = new MutationObserver((mutationsList) => {
          for (const mutation of mutationsList) {
            if (mutation.type === "childList") {
              const cartElement = document.querySelector(
                ".wc-block-cart__main"
              );
              if (cartElement) {
                devLog("Elemento .wc-block-cart__main rilevato nel DOM");

                // aggiorna scroller al cambiamento del DOM carrello
                smoothScroller.update();

                // Interrompi l'osservazione sul contenitore principale
                // observer.disconnect();
              }
            }
          }
        });

        // Avvia l'osservazione sul body
        observer.observe(container, { childList: true, subtree: true });

        devLog(
          "Observer attivato per il caricamento dinamico di .wc-block-cart__main"
        );
      }
    } catch (error) {
      devLog("Errore nella creazione dello scroll", error);
    }
  }

  animateTest() {
    devLog("animateTest triggered");
  }
}

export default Animate;
