export class ProfilChauffeur {
    constructor() {

    }
}

window.editPseudo = () => {
    const display = document.getElementById("pseudoDisplay");
    const form = document.getElementById("pseudoForm");

    if (display && form) {
        display.style.display = "none";
        form.style.display = "block";
    }
};

window.cancelEditPseudo = () => {
    const display = document.getElementById("pseudoDisplay");
    const form = document.getElementById("pseudoForm");

    if (display && form) {
        form.style.display = "none";
        display.style.display = "inline";  // Remet le pseudo visible
    }
};

window.editMail = () => {
    const display = document.getElementById("emailDisplayContainer");
    const form = document.getElementById("emailForm");

    if (display && form) {
        display.style.display = "none";
        form.style.display = "block";
        document.getElementById("mailInput").focus();
    }
};

window.cancelEditMail = () => {
    const display = document.getElementById("emailDisplayContainer");
    const form = document.getElementById("emailForm");

    if (display && form) {
        form.style.display = "none";
        display.style.display = "flex";
    }
};

