document.addEventListener("DOMContentLoaded", function () {


    if(document.querySelector('.select-supplier')){
        reloadSupplier();
    }

    if( document.querySelector('.supplier-table tbody')){
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
        fetch('../Controller/supplierController.php?action=getTable')
            .then(response => response.text())
            .then(html => {
                document.querySelector('.supplier-table tbody').innerHTML = html;
            })
            .catch(error => {
                console.error('Error recargando tabla:', error);
            });
    }

    function reloadSupplier(){
        fetch('../Controller/buyController.php?action=getSupplier')
            .then(response => response.text())
            .then(html => {
                document.querySelector('.select-supplier').innerHTML = html;
            })
            .catch(error => {
                console.error('Error recargando tabla:', error);
            });
    }

    document.getElementById("addSupplierForm").addEventListener("submit", function (e){
        e.preventDefault();
        const formData = new FormData(this);

        fetch("../Controller/supplierController.php", {
            method: "POST",
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            alert(data)
            if(document.querySelector('.select-supplier')){
                reloadSupplier();
                document.getElementById("addSupplierForm").reset();
                document.getElementById("addSubModal-1").style.display = "none";
                
            }

            if( document.querySelector('.supplier-table tbody')){
                reloadTable();
                document.getElementById("addSupplierForm").reset();
                document.getElementById("addSubModal-1").style.display = "none";
            }

            if (data.status === "success") {
                notyf.success(data.message);
            } else {
                notyf.error(data.message);
            }
        })
        .catch(error => console.error("Error en la solicitud:", error));
        });
    });