document.addEventListener("DOMContentLoaded", function () {

    document.getElementById("addInvoiceForm").addEventListener("submit", function (e) {
        e.preventDefault();
        const formData = new FormData(this);


        fetch("../Controller/invoiceController.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            console.log(data);
            
        });
    });

});