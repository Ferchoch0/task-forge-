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

    const chargeModal = document.getElementById("chargeModal");
    const closeChargeModalBtn = document.querySelector(".close-charge");
    const chargeBtns = document.querySelectorAll(".charge-button");

    chargeBtns.forEach(button => {
        button.addEventListener('click', (e) => {
            const clientId = e.target.closest('button').getAttribute('data-id');
            const clientDebtTotal = e.target.closest('button').getAttribute('data-debt-total');
            const clientDebtPaid = e.target.closest('button').getAttribute('data-debt-paid');

            document.getElementById("chargeClientId").value = clientId;
            document.getElementById("clientDebtTotal").value = clientDebtTotal;
            document.getElementById("clientDebtPaid").value = clientDebtPaid;

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

    document.getElementById("chargeClientForm").addEventListener("submit", function (e) {
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
            }, 300);
        });
    });


    // ELIMINACION DE CLIENTES

    document.querySelectorAll('.delete-button').forEach(button => {
        button.addEventListener('click', (e) => {
            const clientId = e.target.closest('button').getAttribute('data-id');

            fetch("../Controller/clientController.php", {
                method: "POST",
                body: JSON.stringify({
                    action: "delete",
                    clientId: clientId
                })
            })
            .then(response => response.text())
            .then(data => {
                location.reload();
            });
        });
    });
    
});
