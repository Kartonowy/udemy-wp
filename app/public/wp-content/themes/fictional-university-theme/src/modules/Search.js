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
    fetch(
      `${univData.root_url}/wp-json/univ/v1/search?term=${this.searchField.value}`
    )
      .then((res) => res.json())
      .then((res) => {
        this.results.innerHTML = `
          <div class="row">
            <div class="one-third">
              <h2 class="search-overlay__section-title">General Information</h2>
              ${
                res.generalInfo.length
                  ? '<ul class="link-list min-list">'
                  : "<p>No general information found</p>"
              }
          
          ${res.generalInfo
            .map(
              (item) =>
                `<li><a href="${item.permalink}">${item.title}</a>${
                  item.author ? " by " + item.author : ""
                }</li>`
            )
            .join("")}
          ${res.generalInfo.length ? "</ul>" : ""}
            </div>
            <div class="one-third">
              <h2 class="search-overlay__section-title">Programs</h2>
              ${
                res.programs.length
                  ? '<ul class="link-list min-list">'
                  : `<p>No programs found <a href="${univData.root_url}/programs">View all programs.</a></p>`
              }
          
          ${res.programs
            .map(
              (item) => `<li><a href="${item.permalink}">${item.title}</a></li>`
            )
            .join("")}
          ${res.programs.length ? "</ul>" : ""}



              <h2 class="search-overlay__section-title">Professors</h2>
              ${
                res.professors.length
                  ? '<ul class="link-list min-list">'
                  : "<p>No professors found</p>"
              }
          
          ${res.professors
            .map(
              (item) => `
                <li class="professor-card__list-item">
                        <a class="professor-card" href="${item.permalink}">
                            <img src="${item.thumbnail}" alt="photo" class="professor-card__image">
                            <span class="professor-card__name">${item.title}</span>
                        </a>
                    </li>
              `
            )
            .join("")}
          ${res.professors.length ? "</ul>" : ""}



            </div>
            <div class="one-third">
              <h2 class="search-overlay__section-title">Campuses</h2>
              ${
                res.campuses.length
                  ? '<ul class="link-list min-list">'
                  : `<p>No campuses found <a href="${univData.root_url}/campus">View all campuses.</a></p>`
              }
          
          ${res.campuses
            .map(
              (item) => `<li><a href="${item.permalink}">${item.title}</a></li>`
            )
            .join("")}
          ${res.campuses.length ? "</ul>" : ""}

              <h2 class="search-overlay__section-title">Events</h2>
              ${
                res.events.length
                  ? ""
                  : `<p>No events found <a href="${univData.root_url}/events">View all events.</a></p>`
              }
          
          ${res.events
            .map(
              (item) => `
                <div class="event-summary">
    <a class="event-summary__date t-center" href="${item.permalink}">
        <span class="event-summary__month">${item.month}</span>
        <span class="event-summary__day">${item.day}</span>
    </a>
    <div class="event-summary__content">
        <h5 class="event-summary__title headline headline--tiny"><a href="${item.permalink}"${item.title}</a></h5>
        <p>${item.description}<a href="${item.permalink}" class="nu gray"> Learn more</a></p>
    </div>
</div>
              `
            )
            .join("")}
          ${res.events.length ? "</ul>" : ""}
            </div>
          </div>
        `;
        this.spinner = false;
      });
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

  openOverlay(e) {
    this.searchOverlay.classList.add("search-overlay--active");
    this.searchField.value = "";
    setTimeout(() => this.searchField.focus(), 301);
    document.querySelector("body").classList.add("body-no-scroll");
    this.overlay = true;
    e.preventDefault();
    return false;
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
