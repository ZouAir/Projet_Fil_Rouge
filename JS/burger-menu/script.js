const burger = document.getElementById("burger");
const nav = document.getElementById("nav");

burger.addEventListener("click", () => {
    if (nav.classList.toggle("nav-open")) {
        burger.setAttribute("aria-expanded", "true");
    } else {
        burger.setAttribute("aria-expanded", "false");
    }
});