document.addEventListener("DOMContentLoaded", function () {

    reloadTable();

    const notyf = new Notyf({
        duration: 3000,
        position: {
          x: 'right',
          y: 'bottom',
        },
        dismissible: true,
      });

    function reloadTable() {
        fetch('../Controller/invoiceController.php?action=getTable')
            .then(response => response.text())
            .then(html => {
                document.querySelector('.invoice-table tbody').innerHTML = html;
                attachEvents();
            })
            .catch(error => {
                console.error('Error recargando tabla:', error);
            });
    }

    if(document.getElementById("addInvoiceForm")){
        document.getElementById("addInvoiceForm").addEventListener("submit", function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            formData.append("action", "addInvoice");
    
    
            fetch("../Controller/invoiceController.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                document.getElementById("addInvoiceForm").reset();
                document.getElementById("addSubModal-1").style.display = "none";
                reloadTable();
            });
        });
    }

    function attachEvents() {
        // // ELIMINACION DE FACTURAS

        document.querySelectorAll('.delete-button').forEach(button => {
            button.addEventListener('click', (e) => {
                const invoiceId = e.target.closest('button').getAttribute('data-id');
    
                if (confirm("¿Estás seguro de que quieres eliminar este producto?")) {
                    fetch("../Controller/invoiceController.php", {
                        method: "POST",
                        body: JSON.stringify({ action: 'delete', id: invoiceId}),
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


    // // CHECK-IN DE FACTURAS

    document.querySelectorAll('.check-in-button').forEach(button => {
        button.addEventListener('click', (e) => {
            const invoiceId = e.target.closest('button').getAttribute('data-id');
            const checkIn = e.target.closest('button').getAttribute('data-check');

            if (confirm("¿Estás seguro de que quieres marcarla como facturada?")) {
                fetch("../Controller/invoiceController.php", {
                    method: "POST",
                    body: JSON.stringify({ action: 'check-in', id: invoiceId,  check: checkIn}),
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