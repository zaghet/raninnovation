import "simplebar"; // or "import SimpleBar from 'simplebar';" if you want to use it manually.
import ResizeObserver from "resize-observer-polyfill";

window.ResizeObserver = ResizeObserver;

class Menu {
  constructor() {
    if (window.jQuery) {
      jQuery(".dropdown-menu").on("click", function (event) {
        event.stopPropagation();
      });
    }

    this.drophover = document.querySelector(".megamenu-drophover-wrapper");
    // Selettore per tutti i dropdown megamenu
    this.dropdowns = document.querySelectorAll(".megamenu.megamenu-centered");

    // Listener per l'apertura dei dropdown
    document
      .querySelectorAll('[data-bs-toggle="dropdown"]')
      .forEach((toggle) => {
        toggle.addEventListener("shown.bs.dropdown", (event) => {
          const dropdownMenu = event.target
            .closest(".menu-item")
            .querySelector(".megamenu");
          if (
            dropdownMenu &&
            dropdownMenu.classList.contains("megamenu-centered")
          ) {
            this.megamenu(dropdownMenu); // Passa il dropdown visibile alla funzione
          }
        });
      });
    // Aggiungi il listener per il ridimensionamento della finestra
    window.addEventListener("resize", () => this.megamenu());

    // Usa ResizeObserver per monitorare i cambiamenti delle dimensioni
    const resizeObserver = new ResizeObserver(() => this.megamenu());
    this.dropdowns.forEach((dropdown) => resizeObserver.observe(dropdown));

    // Usa MutationObserver per monitorare le modifiche alle classi
    const mutationObserver = new MutationObserver(() => this.megamenu());
    this.dropdowns.forEach((dropdown) =>
      mutationObserver.observe(dropdown, {
        attributes: true, // Osserva le modifiche agli attributi
        attributeFilter: ["class"], // Solo cambiamenti di classi
      })
    );

    // apertura dropdown this.drophover
    if (this.drophover) {
      this.megamenu_drophover();
    }

    document
      .getElementById("navbarsTST")
      .addEventListener("hide.bs.collapse", function (e) {
        const bottomHeader = e.currentTarget.closest("#bottom_header");
        if (bottomHeader) {
          bottomHeader.style.backgroundColor = "inherit";
        }
      });
    // $("#navbarsTST").on("hidden.bs.collapse", function () {
    //   console.log("collapsed");
    // });

    document.querySelectorAll(".navbar-collapse").forEach(function (element) {
      element.addEventListener("show.bs.collapse", function (e) {
        const bottomHeader = e.currentTarget.closest("#bottom_header");
        if (bottomHeader) {
          bottomHeader.style.backgroundColor = "#FFF";
        }
      });
    });
    // $(".navbar-collapse").on("shown.bs.collapse", function () {
    //   console.log("already shown");
    // });
  }

  megamenu(dropdown = null) {
    // Se un dropdown specifico viene passato, lavora solo su di esso
    const dropdowns = dropdown
      ? [dropdown]
      : document.querySelectorAll(".megamenu.megamenu-centered");

    dropdowns.forEach((dropdown) => {
      // Controlla se il dropdown corrente è visibile (ha la classe "show")
      if (!dropdown.classList.contains("show")) {
        // dropdown.style.transform = "translateX(-50%) translateY(-4rem)";
        return;
      }

      // Trova il menu item genitore
      const menuItem = dropdown.closest(".menu-item");

      if (!menuItem) {
        console.warn("Menu item non trovato per il megamenu:", dropdown);
        return;
      }

      // Ottieni le coordinate del genitore
      const parentRect = menuItem.getBoundingClientRect();
      const rect = dropdown.getBoundingClientRect();

      // Imposta la posizione iniziale
      // console.log(parentRect.left + " " + parentRect.width);
      // console.log(rect.left + " " + rect.width);
      const dropdownWidth = rect.width / 2; // Larghezza del dropdown
      const centerPosition = parentRect.left + parentRect.width / 2; // Centro del genitore
      const correctedLeft = centerPosition + dropdownWidth / 2; // Calcolo corretto del left

      const leftPX = `${Math.floor(correctedLeft)}px`;
      dropdown.style.left = leftPX;
      // dropdown.style.transform = `translateX(-50% + ${correctedLeft}) translateY(-2rem)`;

      // Controlla overflow a destra
      if (rect.right > window.innerWidth) {
        const overflowRight = rect.right - window.innerWidth;
        dropdown.style.transform = `translateX(calc(-50% - ${overflowRight}px)) translateY(-2rem)`;
      }

      // Controlla overflow a sinistra
      if (rect.left < 0) {
        const overflowLeft = Math.abs(rect.left);
        dropdown.style.transform = `translateX(calc(-50% + ${overflowLeft}px)) translateY(-2rem)`;
      }
    });
  }

  megamenu_drophover() {
    const parent = this.drophover;
    const children = parent.querySelectorAll(".dropdown-toggle"); // Tutti i toggle

    // Itera su ciascun elemento e aggiungi l'evento
    children.forEach((child) => {
      child.addEventListener("click", function (e) {
        // console.log("scateno il click");

        const currentTarget = e.currentTarget; // Elemento che ha ricevuto l'evento (child)

        let dropdownMenuCount = 0;
        let parent = currentTarget; // Inizia con e.currentTarget
        let padding = 0;
        const paddingStep = 200;

        const gparent = currentTarget.closest(".megamenu.dropdown-menu");
        // console.log(Math.abs(gparent.dataset.padding));

        if (currentTarget.classList.contains("show")) {
          if (Math.abs(gparent.dataset.padding) > 0) {
            padding = Math.abs(gparent.dataset.padding) + paddingStep;
            // console.log("si", padding);

            gparent.dataset.padding = padding;
          } else {
            // console.log("no", padding);
            padding = paddingStep;
            gparent.dataset.padding = padding;
          }

          gparent.style.paddingRight = padding.toString() + "px";
        } else {
          padding = Math.abs(gparent.dataset.padding);
          padding = padding - paddingStep;
          if (padding >= 0) {
            gparent.dataset.padding = padding;
            gparent.style.paddingRight = padding.toString() + "px";
          } else {
            gparent.style.paddingRight = "0";
            gparent.dataset.padding = "0";
          }
        }
      });
    });
  } // megameu_drophover
}

export default Menu;
