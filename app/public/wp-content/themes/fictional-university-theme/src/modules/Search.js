import $ from "jquery";

class Search {
  constructor() {
    this.openButton = document.querySelectorAll(".js-search-trigger")[1];
    this.closeButton = document.querySelector(".search-overlay__close");
    this.searchOverlay = document.querySelector(".search-overlay");
    this.searchField = document.querySelector("#search-term");
    this.results = document.querySelector("#search-overlay__results");
    this.overlay = false;
    this.timer;
    this.spinner = false;
    this.prev;
    this.events();
  }

  events() {
    this.openButton.addEventListener("click", this.openOverlay.bind(this));
    this.closeButton.addEventListener("click", this.closeOverlay.bind(this));
    document.addEventListener("keydown", this.handleKeys.bind(this));
    this.searchField.addEventListener("keyup", this.typing.bind(this));
  }

  typing() {
    if (this.searchField.value == this.prev) return;
    clearTimeout(this.timer);

    if (this.searchField.value == "") {
      this.results.innerHTML = "";
      this.spinner = false;
      return;
    }
    if (!this.spinner) {
      this.spinner = true;
      this.results.innerHTML = '<div class="spinner-loader"></div>';
    }
    this.timer = setTimeout(() => {
      this.spinner = false;
      this.results.innerHTML = "search results";
    }, 500);
    this.prev = this.searchField.value;
  }

  handleKeys(e) {
    if (
      e.keyCode == 83 &&
      !this.overlay &&
      !document.querySelector("input:focus, textarea:focus")
    ) {
      this.openOverlay();
    }
    if (e.keyCode == 27 && this.overlay) {
      this.closeOverlay();
    }
  }

  closeOverlay() {
    this.searchOverlay.classList.remove("search-overlay--active");
    document.querySelector("body").classList.remove("body-no-scroll");
    this.overlay = false;
  }

  openOverlay() {
    this.searchOverlay.classList.add("search-overlay--active");
    document.querySelector("body").classList.add("body-no-scroll");
    this.overlay = true;
  }
}

export default Search;
