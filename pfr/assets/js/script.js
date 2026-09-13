const submit = document.querySelector('form');
const email = document.getElementById('email');
const pwd = document.getElementById('pwd');
let error = '';

submit.addEventListener('submit', (event) => {

    if (!email.value.includes('@')) {
        let emailError = document.getElementById('emailError');
        if (emailError === null) {
            error = 'Format email incorrect';
            emailError = document.createElement('div');
            emailError.id = 'emailError';
            emailError.classList.add('error');
            emailError.innerText = error;
            email.closest("form").append(emailError);
        }
    }

    if (pwd.value.trim().length === 0) {
        let pwdError = document.getElementById('pwdError');
        if (pwdError === null) {
            error = 'Veuillez rentrer votre mot de passe svp';
            pwdError = document.createElement('div');
            pwdError.id = 'pwdError';
            pwdError.classList.add('error');
            pwdError.innerText = error;
            pwd.closest("form").append(pwdError);
        }
    }

    if (!email.value.includes('@') || pwd.value.trim().length === 0) {
        event.preventDefault();
    }
});

email.addEventListener('change', (event) => {
    let emailError = document.getElementById('emailError');
    if (email.value.includes('@')) {
        if (emailError !== null) {
            error = '';
            emailError.remove()
        }
    }
})

pwd.addEventListener('input', (event) => {
    let pwdError = document.getElementById('pwdError');
    if (pwd.value.trim().length !== 0) {
        if (pwdError !== null) {
            error = '';
            pwdError.remove()
        }
    }
})

const burger = document.getElementById('burger-btn');
const nav = document.querySelector('nav');
const i = burger.querySelector('i');


burger.addEventListener('click', () => {
    nav.classList.toggle('nav-open');
    i.classList.toggle('bx-menu');
    i.classList.toggle('bx-x');
})