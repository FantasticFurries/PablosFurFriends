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

//home page (phone) - for image slider w/ dots??
const slidesContainer = document.getElementById("img_slides_container");
const totalSlides = slidesContainer.children.length;
const dotsContainer = document.getElementById("dots_container");
let index = 0;

// Create dots based on slide count
for (let i = 0; i < totalSlides; i++) {
  const dot = document.createElement("div");
  dot.classList.add("dot");
  if (i === 0) dot.classList.add("active");
  dotsContainer.appendChild(dot);
}

const dots = dotsContainer.querySelectorAll(".dot");

function showSlide(i) {
  index = (i + totalSlides) % totalSlides;
  slidesContainer.style.transform = `translateX(-${index * 100}%)`;
  updateDots();
}

function updateDots() {
  dots.forEach((dot, i) => {
    dot.classList.toggle("active", i === index);
  });
}

// Auto Slide
let autoSlide = setInterval(() => {
  showSlide(index + 1);
}, 4000);

// Touch swipe support (mobile only)
let startX = 0;
let isDragging = false;

slidesContainer.addEventListener("touchstart", (e) => {
  startX = e.touches[0].clientX;
  isDragging = true;
  clearInterval(autoSlide);
});

slidesContainer.addEventListener("touchend", (e) => {
  if (!isDragging) return;
  let endX = e.changedTouches[0].clientX;
  if (startX - endX > 50) {
    showSlide(index + 1); // swipe left
  } else if (endX - startX > 50) {
    showSlide(index - 1); // swipe right
  }
  autoSlide = setInterval(() => {
    showSlide(index + 1);
  }, 4000);
  isDragging = false;
});
