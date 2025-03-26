
document.addEventListener("DOMContentLoaded", () => {

    // Abrir menu de caja
    const cashModal = document.getElementById("cashModal");
    const openCashModalBtn = document.querySelectorAll(".cash--menu");
    const closeCashModalBtn = document.querySelector(".close-cash");
    
    openCashModalBtn.forEach(button => { 
        button.addEventListener('click', (e) => {
            const typeMov = e.target.closest('button').getAttribute('data-type');
            document.getElementById("typeMov").value = typeMov;
            cashModal.style.display = "flex";
            setTimeout(() => {
                cashModal.style.opacity = "1";
            }, 10);
        });

    });

    if (closeCashModalBtn) {
        closeCashModalBtn.addEventListener("click", () => {
            cashModal.style.opacity = "0";
            setTimeout(() => {
                cashModal.style.display = "none";
            }, 300);
        });
    }

    window.addEventListener("click", (event) => {
        if (event.target === cashModal) {
            cashModal.style.opacity = "0";
            setTimeout(() => {
                cashModal.style.display = "none";
            }, 300);
        }
    });

    // Cargar la tabla de registros de caja


    document.getElementById("addCashForm").addEventListener("submit", function(e){
        e.preventDefault();
        const formData = new FormData(this);

        fetch("../Controller/cashController.php", {
            method: "POST",
            body: formData,
            })
            .then(response => response.text())
            .then(data => {
                location.reload();
            })
            .catch(error => console.error("Error en la solicitud:", error));
    });
});

document.getElementById('downloadPDF').addEventListener('click', function() {
    window.open('cash_pdf.php', '_blank');
});
