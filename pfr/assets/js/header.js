const burger = document.getElementById('burger-btn');
const nav = document.querySelector('nav');

burger.addEventListener('click', () => {
    nav.classList.toggle('nav-open');
})