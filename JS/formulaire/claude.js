//Vérification des saisies de champs dans formulaire

document.getElementById("form").addEventListener("submit", (event) => {
    event.preventDefault();
    console.log("Submit intercepté !")
    let errors = [];
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    document.querySelectorAll(".form-bloc input, .form-bloc select").forEach((input) => {
        if (input.value.trim().length === 0) {
            errors.push({
                message: `Le champ ${input.name} est obligatoire`,
                element: input
            });
        } else if (input.type === "email") {
            if (!regex.test(input.value)) {
                console.log(regex.test(input.value));
                errors.push({
                    message: `Le champ ${input.name} est obligatoire`,
                    element: input
                });
            }
        }
    });
    console.log(errors);
    errors.forEach((error) => {
        error.element.closest(".group-input")
            .querySelector(".msg-erreur")
            .textContent = error.message;
        error.element.style.border = "2px solid red";
    });
});
