const submit = document.querySelector('form');
const oldPwd = document.getElementById('old-pwd');
const newPwd = document.getElementById('new-pwd');
const pwdConfirm = document.getElementById('pwd-confirm');

// Vérification des champs required et des formats.
///////////////////////////////////////////////////

submit.addEventListener('submit', (event) => {
    let hasError = false;

    if (oldPwd.value.length === 0) {
        hasError = true;
        let oldPwdError = document.getElementById('oldPwdError');
        if (oldPwdError === null) {
            oldPwdError = document.createElement('div');
            oldPwdError.id = 'oldPwdError';
            oldPwdError.classList.add('error');
            oldPwdError.innerText = 'Veuillez renseigner votre ancien mot de passe svp';
            oldPwd.closest('form').append(oldPwdError);
        }
    }

    if (newPwd.value.length === 0) {
        hasError = true;
        let newPwdError = document.getElementById('newPwdError');
        if (newPwdError === null) {
            newPwdError = document.createElement('div');
            newPwdError.id = 'newPwdError';
            newPwdError.classList.add('error');
            newPwdError.innerText = 'Veuillez renseigner votre nouveau mot de passe svp';
            newPwd.closest('form').append(newPwdError);
        }
    }

    if (pwdConfirm.value.length === 0) {
        hasError = true;
        let pwdConfirmError = document.getElementById('pwdConfirmError');
        if (pwdConfirmError === null) {
            pwdConfirmError = document.createElement('div');
            pwdConfirmError.id = 'pwdConfirmError';
            pwdConfirmError.classList.add('error');
            pwdConfirmError.innerText = 'Veuillez confirmer votre nouveau mot de passe svp';
            pwdConfirm.closest('form').append(pwdConfirmError);
        }
    }

    if (newPwd.value !== pwdConfirm.value) {
        hasError = true;
        let pwdMatch = document.getElementById('pwdMatch');
        if (pwdMatch === null) {
            pwdMatch = document.createElement('div');
            pwdMatch.id = 'pwdMatch';
            pwdMatch.classList.add('error');
            pwdMatch.innerText = "Les mots de passe ne sont pas identiques";
            newPwd.closest('form').append(pwdMatch);
        }
    }

    if (hasError) {
        event.preventDefault();
    }
});

oldPwd.addEventListener('change', (event) => {
    const oldPwdError = document.getElementById('oldPwdError');
    if (oldPwd.value.length !== 0 && oldPwdError !== null) {
        oldPwdError.remove()
    }
})

newPwd.addEventListener('change', (event) => {
    const newPwdError = document.getElementById('newPwdError');
    if (newPwd.value.length !== 0 && newPwdError !== null) {
        newPwdError.remove()
    }
})

pwdConfirm.addEventListener('change', (event) => {
    const pwdConfirmError = document.getElementById('pwdConfirmError');
    const pwdMatch = document.getElementById('pwdMatch');
    if (pwdConfirm.value.length !== 0 && pwdConfirmError !== null) {
        pwdConfirmError.remove()
    }
    if (newPwd.value === pwdConfirm.value && pwdMatch !== null) {
        pwdMatch.remove()
    }
})

//Vérification du mot de passe
//////////////////////////////
const pwdLength = document.getElementById('pwd-criteria-length');
const pwdSpecial = document.getElementById('pwd-criteria-special');
const pwdUppercase = document.getElementById('pwd-criteria-uppercase');
const pwdNumeric = document.getElementById('pwd-criteria-numeric');

newPwd.addEventListener('keyup', () => {
    // 8 caractères minimum :
    if (newPwd.value.length >= 8) {
        pwdLength.classList.add('success');
    } else {
        pwdLength.classList.remove('success');
    }

    // caractère spécial minimum :
    let regExSpecial = /[#@!?$%&]/;
    if (regExSpecial.test(newPwd.value)) {
        pwdSpecial.classList.add('success');
    } else {
        pwdSpecial.classList.remove('success');
    }
    // caractère majuscule minimum :
    let regExUpper = /[A-Z]/;
    if (regExUpper.test(newPwd.value)) {
        pwdUppercase.classList.add('success');
    } else {
        pwdUppercase.classList.remove('success');
    }

    // caractère numérique minimum :
    let regExNumeric = /[0-9]/;
    if (regExNumeric.test(newPwd.value)) {
        pwdNumeric.classList.add('success');
    } else {
        pwdNumeric.classList.remove('success');
    }
})


