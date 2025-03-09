document.addEventListener("DOMContentLoaded", function () {
    const modal = document.getElementById("addInvoiceModal");
    const openModalBtn = document.querySelector(".invoice-menu--button");
    const closeModalBtn = document.querySelector(".close");

    openModalBtn.addEventListener("click", () => modal.style.display = "flex");
    closeModalBtn.addEventListener("click", () => modal.style.display = "none");

    window.addEventListener("click", (event) => {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    });

    document.getElementById("addInvoiceForm").addEventListener("submit", function (e) {
        e.preventDefault();
        const formData = new FormData();
        formData.append("action", "add");
        formData.append("typeInvoice", document.getElementById("typeInvoice").value);
        formData.append("cuit", document.getElementById("cuit").value);
        formData.append("address", document.getElementById("address").value);
        formData.append("buinessName", document.getElementById("buinessName").value);
        formData.append("contact", document.getElementById("contact").value);


        fetch("../Controller/invoiceController.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            modal.style.display = "none";
            
        });
    });

});