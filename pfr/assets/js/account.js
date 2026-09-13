const submit = document.querySelector('form');
const name = document.getElementById('name');
const firstName = document.getElementById('firstname');
const email = document.getElementById('email');
const phone = document.getElementById('phone');
const phoneRegEx = /^[0-9]{10}$/;
const postal = document.getElementById('postal')
const postalRegEx = /^\d{5}$/;

// Vérification des champs required et des formats
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

    if ((!phoneRegEx.test(phone.value))) {
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

    if ((!postalRegEx.test(postal.value))) {
        hasError = true;
        let postalError = document.getElementById('postalError');
        if (postalError === null) {
            postalError = document.createElement('div');
            postalError.id = 'postalError';
            postalError.classList.add('error');
            postalError.innerText = 'Format code postal incorrect';
            postal.closest('form').append(postalError);
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
    if (phoneRegEx.test(phone.value) && phoneError !== null) {
        phoneError.remove()
    }
})

postal.addEventListener('change', (event) => {
    const postalError = document.getElementById('postalError');
    if (postalRegEx.test(postal.value) && postalError !== null) {
        postalError.remove()
    }
})


