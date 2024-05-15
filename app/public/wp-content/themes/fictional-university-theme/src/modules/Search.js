import $ from "jquery";

class Search {
  constructor() {
    this.insertOverlay();
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

  async typing() {
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
      this.getResults();
    }, 500);
    this.prev = this.searchField.value;
  }

  async getResults() {
    Promise.all([
      await fetch(
        `${univData.root_url}/wp-json/wp/v2/pages?search=${
          (this, this.searchField.value)
        }`
      ).then((res) => res.json()),
      await fetch(
        `${univData.root_url}/wp-json/wp/v2/posts?search=${
          (this, this.searchField.value)
        }`
      ).then((re) => re.json()),
    ]).then(
      async (res) => {
        let combined = res[0].concat(res[1]);
        this.results.innerHTML = `
          <h2 class="search-overlay__section-title">General Information</h2>
          ${
            combined.length
              ? '<ul class="link-list min-list">'
              : "<p>No general information found</p>"
          }
          
          ${combined
            .map(
              (item) =>
                `<li><a href="${item.link}">${item.title.rendered}</a></li>`
            )
            .join("")}
          ${combined.length ? "</ul>" : ""}
        `;
        this.spinner = false;
      },
      (this.results.innerHTML = "Unexpected error,please try again.")
    );
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
    this.searchField.value = "";
    setTimeout(() => this.searchField.focus(), 301);
    document.querySelector("body").classList.add("body-no-scroll");
    this.overlay = true;
  }

  insertOverlay() {
    document.body.innerHTML += `
    <div class="search-overlay">
      <div class="search-overlay__top">
        <div class="container">
          <i class="fa fa-search search-overlay__icon" aria-hidden="true"></i>
          <input type="text" class="search-term" placeholder="What are you looking for" id="search-term" autocomplete="off">
          <i class="fa fa-window-close search-overlay__close" aria-hidden="true"></i>
        </div>
      </div>
      <div class="container">
        <div id="search-overlay__results"></div>
      </div>
    </div>
    `;
  }
}

export default Search;
