document.addEventListener("DOMContentLoaded", function () {

    if(document.querySelector('.select-client')){
        reloadClient();
    }

    if( document.querySelector('.client-table tbody')){
        reloadTable();
    }

    const notyf = new Notyf({
        duration: 3000,
        position: {
          x: 'right',
          y: 'bottom',
        },
        dismissible: true,
      });

    // RECARGA DE TABLA


    function reloadTable() {
        fetch('../Controller/clientController.php?action=getTable')
            .then(response => response.text())
            .then(html => {
                document.querySelector('.client-table tbody').innerHTML = html;
                attachEvents();
            })
            .catch(error => {
                console.error('Error recargando tabla:', error);
            });
    }

    function reloadClient(){
        fetch('../Controller/sellController.php?action=getClients')
            .then(response => response.text())
            .then(html => {
                document.querySelector('.select-client').innerHTML = html;
            })
            .catch(error => {
                console.error('Error recargando tabla:', error);
            });
    }

    document.getElementById("addClientForm").addEventListener("submit", function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        formData.append("action", "addClient");


        fetch("../Controller/clientController.php", {
            method: "POST",
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            
            if(document.querySelector('.select-client')){
                reloadClient();
                document.getElementById("addSubModal-1").style.display = "none";
                document.getElementById("addClientForm").reset()
            }

            if( document.querySelector('.client-table tbody')){
                reloadTable();
                document.getElementById("addSubModal-1").style.display = "none";
                document.getElementById("addClientForm").reset()
            }

            if (data.status === "success") {
                notyf.success(data.message);
            } else {
                notyf.error(data.message);
            }
        });
    });

    const chargeModal = document.getElementById("chargeModal");
    const closeChargeModalBtn = document.querySelector(".close-charge");
    const chargeBtns = document.querySelectorAll(".charge-button");
    const chargeClientForm = document.getElementById("chargeClientForm");

    if (chargeClientForm) {
        chargeClientForm.addEventListener("submit", function (e) {
            e.preventDefault();

            const formData = new FormData(this);
            formData.append("action", "chargeClient");

        fetch("../Controller/clientController.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            chargeModal.style.opacity = "0";
            setTimeout(() => {
                chargeModal.style.display = "none";
                document.getElementById("chargeClientForm").reset();
                chargeModal.style.display = "none";
                reloadTable();

                if (data.status === "success") {
                    notyf.success(data.message);
                } else {
                    notyf.error(data.message);
                }
            }, 300);
            });
        });
    }


    function attachEvents() {

    // MODAL DE EDICION DE CLIENTES

    const chargeModal = document.getElementById("chargeModal");
    const closeChargeModalBtn = document.querySelector(".close-charge");
    const chargeBtns = document.querySelectorAll(".charge-button");
    const debtTotalInput = document.getElementById("clientDebtTotal");
    const debtPaidInput = document.getElementById("clientDebtPaid");

    function validateDebtPaid() {
        const maxDebt = parseFloat(debtTotalInput.value) || 0;
        const currentPaid = parseFloat(debtPaidInput.value) || 0;

        if (currentPaid > maxDebt) {
            debtPaidInput.value = maxDebt;
        } else if (currentPaid < 0) {
            debtPaidInput.value = 0;
        }
    }

        debtPaidInput.addEventListener("input", validateDebtPaid);

        chargeBtns.forEach(button => {
            button.addEventListener('click', (e) => {
                const clientId = e.target.closest('button').getAttribute('data-id');
                const clientTotalDebt = e.target.closest('button').getAttribute('data-debt');
                document.getElementById("chargeClientId").value = clientId;
                debtTotalInput.value = clientTotalDebt;

                debtPaidInput.value = "";
                setTimeout(validateDebtPaid, 100);

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
                .then(response => response.json())
                .then(data => {
                    reloadTable();

                    if (data.status === "success") {
                        notyf.success(data.message);
                    } else {
                        notyf.error(data.message);
                    }
                }); 
            }
        }); 
    });

}
    
});


function filterTable() {
    let filter = document.getElementById("searchInput").value.toLowerCase();
    let rows = document.querySelectorAll(".client-table tbody tr");

    rows.forEach(rows => {
        let product = rows.cells[0].textContent.toLowerCase();
        rows.style.display = product.includes(filter) ? "" : "none";
    });
}
