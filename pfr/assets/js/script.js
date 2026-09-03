const email = document.getElementById('email');
let error = '';

email.addEventListener('blur', (event) => {
    let emailError = document.getElementById('emailError');

    if (!email.value.includes('@')) {
        if (emailError === null) {
            error = 'Format email incorrect';
            emailError = document.createElement('div');
            emailError.id = 'emailError';
            emailError.classList.add('error');
            emailError.innerText = error;
            email.closest('form').append(emailError);
        }
    } else {
        if (emailError !== null) {
            error = '';
            emailError.remove()
        }
    }
});

const pwd = document.getElementById('pwd');

pwd.addEventListener('blur', (event) => {
    let pwdError = document.getElementById('pwdError');

    if (pwd.value.trim().length === 0) {
        if (pwdError === null) {
            error = 'Veuillez rentrer votre mot de passe svp';
            pwdError = document.createElement('div');
            pwdError.id = 'pwdError';
            pwdError.classList.add('error');
            pwdError.innerText = error;
            pwd.closest('form').append(pwdError);
        }
    } else {
        if (pwdError !== null) {
            error = '';
            pwdError.remove()
        }
    }
});

document.querySelector('form').addEventListener('submit', (event) => {
    if (!email.value.includes('@') || pwd.value.trim().length === 0) {
        event.preventDefault();
        console.log('Oups!')
    }
})

