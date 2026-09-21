//Traitement modale information (succès ou erreur après action)
const modalConfirm = document.querySelector('.modal-confirm');
const Icon = document.querySelector('.modal-icon');
const p = document.querySelector('.modal-confirm p');
const button = document.querySelector('.modal-confirm button');

if (modalMessage !== null) {
    p.innerText = modalMessage;
    Icon.innerHTML = modalIcon;
    modalConfirm.classList.add('is-open');
}

button.addEventListener('click', (e) => {
    modalConfirm.classList.remove('is-open');
})


//Traitement modale confirmation (avant suppression)
const forms = document.querySelectorAll('.form-delete');
const modalAlert = document.querySelector('.modal-alert');
const confirmButton = modalAlert.querySelector('.modal-cta button');
const cancelButton = modalAlert.querySelector('#cancel');
let currentForm = null;

forms.forEach((form) => {
    form.addEventListener('submit', (event) => {
        event.preventDefault();
        modalAlert.classList.add('is-open');
        currentForm = form;
    })
})

confirmButton.addEventListener('click', () => {
    currentForm.submit();
    modalAlert.classList.remove('is-open');
})

cancelButton.addEventListener('click', () => {
    modalAlert.classList.remove('is-open');
})