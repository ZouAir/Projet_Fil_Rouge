const burger = document.getElementById('burger-btn');
const nav = document.querySelector('nav');
const i = burger.querySelector('i');


burger.addEventListener('click', () => {
    nav.classList.toggle('nav-open');
    i.classList.toggle('bx-menu');
    i.classList.toggle('bx-x');
})

