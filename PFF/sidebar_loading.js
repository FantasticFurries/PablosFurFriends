//loading screen js
window.addEventListener("load", () => {
    const loadingScreen = document.getElementById("loading-screen");
    const mainContent = document.getElementById("main-content");

    if (loadingScreen) {
        loadingScreen.classList.add("hide");

        setTimeout(() => {
            loadingScreen.style.display = "none";
            if (mainContent) {
                mainContent.style.display = "block";
            }
        }, 500);
    }
});

//sidebar for phones
const menuToggle = document.getElementById("menu_phone");
const sidebar = document.getElementById("sidebar");
const overlay = document.getElementById("dim_overlay");

menuToggle.addEventListener("click", () => {
  sidebar.classList.toggle("open");
  overlay.classList.toggle("show");
});

overlay.addEventListener("click", () => {
    sidebar.classList.remove("open");
    overlay.classList.remove("show");
});

//sidebar appointments dropdown
const dropdownToggle = document.querySelector(".dropdown_toggle");
const appointmentsSidebar = document.getElementById("appointments_sidebar");
const arrowDown = document.getElementById("arrow_down");
const arrowUp = document.getElementById("arrow_up");

dropdownToggle.addEventListener("click", () => {
  appointmentsSidebar.style.display =
    appointmentsSidebar.style.display === "flex" ? "none" : "flex";

  arrowDown.style.display =
    arrowDown.style.display === "none" ? "inline" : "none";
  arrowUp.style.display =
    arrowUp.style.display === "none" ? "inline" : "none";
});