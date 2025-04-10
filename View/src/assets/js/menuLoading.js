document.addEventListener("DOMContentLoaded", function () {
    // CARGA DE PRODUCTOS
    const modal = document.getElementById("addModal");
    const subModal = document.getElementById("addSubModal-1");
    const subModal2 = document.getElementById("addSubModal-2");

    const openModalBtn = document.querySelector(".menu--button");
    const closeModalBtn = document.querySelector(".close");
   
    const openSubModalBtn = document.querySelector(".submenu--button");
    const closeSubModalBtn = document.querySelector(".close--sub");

    const openSubModalBtn2 = document.querySelector(".submenu--button2");
    const closeSubModalBtn2 = document.querySelector(".close--sub2");

    if (openModalBtn) {
        openModalBtn.addEventListener("click", () => {
            modal.style.display = "flex";
            setTimeout(() => {
                modal.style.opacity = "1";
            }, 10); // Pequeño retraso para permitir que la transición ocurra
        });
    }

    if (openSubModalBtn) {
        openSubModalBtn.addEventListener("click", () => {
            subModal.style.display = "flex";
            setTimeout(() => {
                subModal.style.opacity = "1";
            }, 10); // Pequeño retraso para permitir que la transición ocurra
        });
    }

    if (openSubModalBtn2) {
        openSubModalBtn2.addEventListener("click", () => {
            subModal2.style.display = "flex";
            setTimeout(() => {
                subModal2.style.opacity = "1";
            }, 10); // Pequeño retraso para permitir que la transición ocurra
        });
    }

    if (closeModalBtn) {
        closeModalBtn.addEventListener("click", () => {
            modal.style.opacity = "0";
            setTimeout(() => {
                modal.style.display = "none";
            }, 300); // Tiempo de la transición
        });
    }

    if (closeSubModalBtn) {
        closeSubModalBtn.addEventListener("click", () => {
            subModal.style.opacity = "0";
            setTimeout(() => {
                subModal.style.display = "none";
            }, 300); // Tiempo de la transición
        });
    }

    if (closeSubModalBtn2) {
        closeSubModalBtn2.addEventListener("click", () => {
            subModal2.style.opacity = "0";
            setTimeout(() => {
                subModal2.style.display = "none";
            }, 300); // Tiempo de la transición
        });
    }

    window.addEventListener("click", (event) => {
        if (event.target === modal) {
            modal.style.opacity = "0";
            setTimeout(() => {
                modal.style.display = "none";
            }, 300); // Tiempo de la transición
        } else if (event.target === subModal) {
            subModal.style.opacity = "0";
            setTimeout(() => {
                subModal.style.display = "none";
            }, 300); // Tiempo de la transición
        } else if (event.target === subModal2) {
            subModal2.style.opacity = "0";
            setTimeout(() => {
                subModal2.style.display = "none";
            }, 300); // Tiempo de la transición
        }
    });



    

});