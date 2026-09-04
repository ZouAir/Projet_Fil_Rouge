const submit = document.querySelector('form');
const name = document.getElementById('name');
const firstName = document.getElementById('firstname');
const email = document.getElementById('email');
const phone = document.getElementById('phone');
const regEx = /[0-9]+/;
const pwd = document.getElementById('pwd');
const pwdConfirm = document.getElementById('pwd-confirm');

submit.addEventListener('submit', (event) => {
    let hasError = false;

    if (name.value.trim().length === 0) {
        hasError = true;
        let nameError = document.getElementById('nameError');
        if (nameError === null) {
            nameError = document.createElement('div');
            nameError.id = 'nameError';
            nameError.classList.add('error');
            nameError.innerText = 'Veuillez renseigner votre nom svp';
            name.closest('form').append(nameError);
        }
    }

    if (firstName.value.trim().length === 0) {
        hasError = true;
        let firstNameError = document.getElementById('firstNameError');
        if (firstNameError === null) {
            firstNameError = document.createElement('div');
            firstNameError.id = 'firstNameError';
            firstNameError.classList.add('error');
            firstNameError.innerText = 'Veuillez renseigner votre prénom svp';
            firstName.closest('form').append(firstNameError);
        }
    }

    if (!email.value.includes('@')) {
        hasError = true;
        let emailError = document.getElementById('emailError');
        if (emailError === null) {
            emailError = document.createElement('div');
            emailError.id = 'emailError';
            emailError.classList.add('error');
            emailError.innerText = 'Format email incorrect';
            email.closest('form').append(emailError);
        }
    }

    if ((!regEx.test(phone.value))) {
        hasError = true;
        let phoneError = document.getElementById('phoneError');
        if (phoneError === null) {
            phoneError = document.createElement('div');
            phoneError.id = 'phoneError';
            phoneError.classList.add('error');
            phoneError.innerText = 'Format numéro de téléphone incorrect';
            phone.closest('form').append(phoneError);
        }
    }

    if (pwd.value.trim().length === 0) {
        hasError = true;
        let pwdError = document.getElementById('pwdError');
        if (pwdError === null) {
            pwdError = document.createElement('div');
            pwdError.id = 'pwdError';
            pwdError.classList.add('error');
            pwdError.innerText = 'Veuillez renseigner votre mot de passe svp';
            pwd.closest('form').append(pwdError);
        }
    }

    if (pwdConfirm.value.trim().length === 0) {
        hasError = true;
        let pwdConfirmError = document.getElementById('pwdConfirmError');
        if (pwdConfirmError === null) {
            pwdConfirmError = document.createElement('div');
            pwdConfirmError.id = 'pwdConfirmError';
            pwdConfirmError.classList.add('error');
            pwdConfirmError.innerText = 'Veuillez confirmer votre mot de passe svp';
            pwd.closest('form').append(pwdConfirmError);
        }
    }

    if (pwd.value !== pwdConfirm.value) {
        hasError = true;
        let pwdMatch = document.getElementById('pwdMatch');
        if (pwdMatch === null) {
            pwdMatch = document.createElement('div');
            pwdMatch.id = 'pwdMatch';
            pwdMatch.classList.add('error');
            pwdMatch.innerText = "Les mots de passe ne sont pas identiques";
            pwd.closest('form').append(pwdMatch);
        }
    }

    if (hasError) {
        event.preventDefault();
    }
});

name.addEventListener('change', (event) => {
    const nameError = document.getElementById('nameError');
    if (name.value.trim().length !== 0 && nameError !== null) {
        nameError.remove()
    }
})

firstName.addEventListener('change', (event) => {
    const firstNameError = document.getElementById('firstNameError');
    if (firstName.value.trim().length !== 0 && firstNameError !== null) {
        firstNameError.remove()
    }
})

email.addEventListener('change', (event) => {
    const emailError = document.getElementById('emailError');
    if (email.value.includes('@') && emailError !== null) {
        emailError.remove()
    }
})

phone.addEventListener('change', (event) => {
    const phoneError = document.getElementById('phoneError');
    if (regEx.test(phone.value) && phoneError !== null) {
        phoneError.remove()
    }
})

pwd.addEventListener('input', (event) => {
    const pwdError = document.getElementById('pwdError');
    if (pwd.value.trim().length !== 0 && pwdError !== null) {
        pwdError.remove()
    }
})

pwdConfirm.addEventListener('input', (event) => {
    const pwdConfirmError = document.getElementById('pwdConfirmError');
    const pwdMatch = document.getElementById('pwdMatch');
    if (pwdConfirm.value.trim().length !== 0 && pwdConfirmError !== null) {
        pwdConfirmError.remove()
    }
    if (pwd.value === pwdConfirm.value && pwdMatch !== null) {
        pwdMatch.remove()
    }
})



