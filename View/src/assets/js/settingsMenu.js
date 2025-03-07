window.addEventListener("DOMContentLoaded", function () {
    const profilenGroup = document.getElementById("content-profile");
    const firstRadio = document.getElementById("value-1");

    if (firstRadio.checked) {
        profilenGroup.classList.add("show");
    }


document.querySelectorAll('input[name="value-radio"]').forEach((radio) => {
    radio.addEventListener("change", function () {

        const profileGroup = document.getElementById("content-profile");
        const configGroup = document.getElementById("content-config");
        const utilsGroup = document.getElementById("content-utils");

        if (this.id === "value-1" && this.checked) {
            // Mostrar perfil y ocultar configuración
            profileGroup.classList.add("show");
            configGroup.classList.remove("show");
            utilsGroup.classList.remove("show");
        } else if (this.id === "value-2" && this.checked) {
            // Mostrar configuración y ocultar perfil
            configGroup.classList.add("show");
            profileGroup.classList.remove("show");
            utilsGroup.classList.remove("show");
        } else if (this.id === "value-3" && this.checked) {
            // Mostrar configuración y perfil
            utilsGroup.classList.add("show");
            configGroup.classList.remove("show");
            profileGroup.classList.remove("show");
        }
    });
});

});