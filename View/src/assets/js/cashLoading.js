
document.addEventListener("DOMContentLoaded", () => {

    reloadTable();

    reloadTableTotal();

    const notyf = new Notyf({
        duration: 3000,
        position: {
          x: 'right',
          y: 'bottom',
        },
        dismissible: true,
      });

    function reloadTable() {
        fetch('../Controller/cashController.php?action=getTable')
            .then(response => response.text())
            .then(html => {
                document.querySelector('.cash-data').innerHTML = html;
            })
            .catch(error => {
                console.error('Error recargando tabla:', error);
            });
    }

    function reloadTableTotal() {
        fetch('../Controller/cashController.php?action=getTableTotal')
            .then(response => response.text())
            .then(html => {
                document.querySelector('.cash-total').innerHTML = html;
            })
            .catch(error => {
                console.error('Error recargando tabla:', error);
            });
    }


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
        formData.append("action", "addCash");

        fetch("../Controller/cashController.php", {
            method: "POST",
            body: formData,
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById("addCashForm").reset();
                cashModal.style.display = "none";
                
                reloadTable();
                reloadTableTotal();

                if (data.status === "success") {
                    notyf.success(data.message);
                } else {
                    notyf.error(data.message);
                }
            })
            .catch(error => console.error("Error en la solicitud:", error));
    });
});

document.getElementById('downloadPDF').addEventListener('click', function() {
    window.open('cash_pdf.php', '_blank');
});
