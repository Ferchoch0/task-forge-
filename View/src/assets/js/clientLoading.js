document.addEventListener("DOMContentLoaded", function () {

    document.getElementById("addClientForm").addEventListener("submit", function (e) {
        e.preventDefault();
        const formData = new FormData(this);

        fetch("../Controller/clientController.php", {
            method: "POST",
            body: formData,
        })
        .then(response => response.text())
        .then(data => {
            location.reload();
        });
    });


    // MODAL DE EDICION DE CLIENTES

    const chargeModal = document.getElementById("chargeModal");
    const closeChargeModalBtn = document.querySelector(".close-charge");
    const chargeBtns = document.querySelectorAll(".charge-button");

        chargeBtns.forEach(button => {
            button.addEventListener('click', (e) => {
                const clientId = e.target.closest('button').getAttribute('data-id');
                const clientTotalDebt = e.target.closest('button').getAttribute('data-debt');
                document.getElementById("chargeClientId").value = clientId;
                document.getElementById("clientDebtTotal").value = clientTotalDebt;

                chargeModal.style.display = "flex";
                setTimeout(() => {
                    chargeModal.style.opacity = "1";
                }, 10);
            });
        });

        if (closeChargeModalBtn) {
            closeChargeModalBtn.addEventListener("click", () => {
                chargeModal.style.opacity = "0";
                setTimeout(() => {
                    chargeModal.style.display = "none";
                }, 300);
            });
        }

        window.addEventListener("click", (event) => {
            if (event.target === chargeModal) {
                chargeModal.style.opacity = "0";
                setTimeout(() => {
                    chargeModal.style.display = "none";
                }, 300);
            }
        });


        const chargeClientForm = document.getElementById("chargeClientForm");

    if (chargeClientForm) {
        chargeClientForm.addEventListener("submit", function (e) {
            e.preventDefault();

            const formData = new FormData(this);
            formData.append("action", "charge");

        fetch("../Controller/clientController.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            chargeModal.style.opacity = "0";
            setTimeout(() => {
                chargeModal.style.display = "none";
                location.reload();
            }, 300);
        });
    });
}
    




    // MODAL DE HISTORIAL DE CLIENTES

    const historyModal = document.getElementById("historyModal");
    const closeHistoryModalBtn = document.querySelector(".close-history");
    const historyBtns = document.querySelectorAll(".history-button");

    historyBtns.forEach(button => {
        button.addEventListener('click', (e) => {
            const clientId = e.target.closest('button').getAttribute('data-id');
            document.getElementById("historyClientId").value = clientId;
    
            // Realizar solicitud GET para obtener la deuda del cliente
            fetch(`../Controller/clientController.php?client_id=${clientId}`)
                .then(response => response.json())
                .then(data => {
                    // Llenar el historial en la tabla
                    const historyTable = document.getElementById("historyTable");
                    historyTable.innerHTML = ''; // Limpiar la tabla antes de agregar los datos
                    data.forEach(debt => {
                        const row = document.createElement("tr");
                        row.innerHTML = `
                            <td>${debt.debt_type === 0 ? 'Débito' : 'Crédito'}</td>
                            <td>$${debt.amount}</td>
                            <td>${debt.fech}</td>
                        `;
                        historyTable.appendChild(row);
                    });
                })
                .catch(error => {
                    console.error('Error al obtener historial:', error);
                });
    
            historyModal.style.display = "flex";
            setTimeout(() => {
                historyModal.style.opacity = "1";
            }, 10);
        });
    });

    if (closeHistoryModalBtn) {
        closeHistoryModalBtn.addEventListener("click", () => {
            historyModal.style.opacity = "0";
            setTimeout(() => {
                historyModal.style.display = "none";
            }, 300);
        });
    }

        window.addEventListener("click", (event) => {
            if (event.target === historyModal) {
                historyModal.style.opacity = "0";
                setTimeout(() => {
                    historyModal.style.display = "none";
                }, 300);
            }
        });


    // // ELIMINACION DE CLIENTES

    document.querySelectorAll('.delete-button').forEach(button => {
        button.addEventListener('click', (e) => {
            const clientId = e.target.closest('button').getAttribute('data-id');

            if (confirm("¿Estás seguro de que quieres eliminar este producto?")) {
                fetch("../Controller/clientController.php", {
                    method: "POST",
                    body: JSON.stringify({ action: 'delete', id: clientId }),
                    headers: { "Content-Type": "application/json" },
                })
                .then(response => response.text())
                .then(data => {
                    alert(data);
                    location.reload();
                }); 
            }
        }); 
    });
    
});
