document.addEventListener("DOMContentLoaded", function () {

    reloadTable();

    function reloadTable() {
        fetch('../Controller/invoiceController.php?action=getTable')
            .then(response => response.text())
            .then(html => {
                document.querySelector('.invoice-table tbody').innerHTML = html;
            })
            .catch(error => {
                console.error('Error recargando tabla:', error);
            });
    }

    if(document.getElementById("addInvoiceForm")){
        document.getElementById("addInvoiceForm").addEventListener("submit", function (e) {
            e.preventDefault();
            const formData = new FormData(this);
    
    
            fetch("../Controller/invoiceController.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.text())
            .then(data => {
    
            });
        });
    }
    

});