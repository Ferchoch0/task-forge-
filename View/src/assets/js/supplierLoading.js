document.addEventListener("DOMContentLoaded", function () {

    document.getElementById("addSupplierForm").addEventListener("submit", function (e){
        e.preventDefault();
        const formData = new FormData(this);

        fetch("../Controller/supplierController.php", {
            method: "POST",
            body: formData,
        })
        .then(response => response.text())
        .then(data => {
            console.log(data); // Ver errores en la consola
            location.reload(); // Si todo está bien, recarga la página
        })
        .catch(error => console.error("Error en la solicitud:", error));
        });
    });