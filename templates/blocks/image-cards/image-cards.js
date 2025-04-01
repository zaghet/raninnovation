import "./_image-cards.scss";

document.addEventListener("DOMContentLoaded", function () {
  const cards = document.querySelectorAll(".block__image-cards .card-image");

  // Funzione per rimuovere la classe .active da tutte le card
  const removeActiveClass = () => {
    cards.forEach((card) => card.classList.remove("active"));
  };

  // Aggiungi evento hover alle card
  cards.forEach((card) => {
    card.addEventListener("mouseenter", () => {
      removeActiveClass(); // Rimuove l'attuale .active
      card.classList.add("active"); // Aggiunge .active alla card corrente
    });

    card.addEventListener("mouseleave", () => {
      removeActiveClass(); // Rimuove l'attuale .active
      // Imposta nuovamente la prima card come attiva
      if (cards[0]) cards[0].classList.add("active");
    });
  });

  // Imposta la prima card come attiva al caricamento della pagina
  if (cards[0]) cards[0].classList.add("active");
});
