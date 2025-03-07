  document.addEventListener("DOMContentLoaded", function () {
     // CARGA DE PRODUCTOS
    const modal = document.getElementById("addProductModal");
    const openModalBtn = document.querySelector(".stock-menu--button");
    const closeModalBtn = document.querySelector(".close");

    openModalBtn.addEventListener("click", () => modal.style.display = "block");
    closeModalBtn.addEventListener("click", () => modal.style.display = "none");

    window.addEventListener("click", (event) => {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    });

    document.getElementById("addProductForm").addEventListener("submit", function (e) {
        e.preventDefault();
        const formData = new FormData();
        formData.append("action", "add");
        formData.append("productName", document.getElementById("productName").value);
        formData.append("productStock", document.getElementById("productStock").value);
        formData.append("productMinStock", document.getElementById("productMinStock").value);
        formData.append("productTypeAmount", document.getElementById("productTypeAmount").value);
        formData.append("productPrice", document.getElementById("productPrice").value);


        fetch("../Controller/stockController.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            modal.style.display = "none";
            location.reload();
        });
    });


     // EDICION DE PRODUCTOS
    
    const editModal = document.getElementById("editProductModal");
    const openEditModalBtn = document.querySelectorAll(".edit-button");
    const closeEditModalBtn = document.querySelector(".close-edit");

    openEditModalBtn.forEach(button => {
        button.addEventListener('click', (e) => {
            const productId = e.target.closest('button').getAttribute('data-id');
            const productName = e.target.closest('button').getAttribute('data-name');
            const productStock = e.target.closest('button').getAttribute('data-stock');
            const productMinStock = e.target.closest('button').getAttribute('data-min-stock');
            const productTypeAmount = e.target.closest('button').getAttribute('data-type-amount');
            const productPrice = e.target.closest('button').getAttribute('data-price');

            console.log("Product Name:", productName);
            console.log("Product Stock:", productStock);


            document.getElementById("editProductId").value = productId;
            document.getElementById("editProductName").value = productName;
            document.getElementById("editProductStock").value = productStock;
            document.getElementById("editProductMinStock").value = productMinStock;
            document.getElementById("editProductTypeAmount").value = productTypeAmount;
            document.getElementById("editProductPrice").value = productPrice;

            editModal.style.display = "block";
        });
    });


    closeEditModalBtn.addEventListener("click", () => editModal.style.display = "none");

    window.addEventListener("click", (event) => {
        if (event.target === editModal) {
            editModal.style.display = "none";
        }
    });

   

    document.getElementById("editProductForm").addEventListener("submit", function (e) {
        e.preventDefault();



        const formData = new FormData();
        formData.append("action", "edit");
        formData.append("editProductId", document.getElementById("editProductId").value);
        formData.append("editProductName", document.getElementById("editProductName").value);
        formData.append("editProductStock", document.getElementById("editProductStock").value);
        formData.append("editProductMinStock", document.getElementById("editProductMinStock").value);
        formData.append("editProductTypeAmount", document.getElementById("editProductTypeAmount").value);
        formData.append("editProductPrice", document.getElementById("editProductPrice").value);

        fetch("../Controller/stockController.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            editModal.style.display = "none";
            location.reload();
        });
    });


    // ELIMINACION DE PRODUCTOS

    document.querySelectorAll('.delete-button').forEach(button => {
        button.addEventListener('click', (e) => {
            const productId = e.target.closest('button').getAttribute('data-id');
            
            if (confirm("¿Estás seguro de que quieres eliminar este producto?")) {
                // Llamar a la función para eliminar el producto mediante AJAX
                fetch("../Controller/stockController.php", {
                    method: "POST",
                    body: JSON.stringify({ action: 'delete', id: productId }),
                    headers: { "Content-Type": "application/json" },
                })
                .then(response => response.text())
                .then(data => {
                    alert(data);
                    location.reload(); // Recargar la página para reflejar los cambios
                });
            }
        });
    });
});

function filterTable() {
    let filter = document.getElementById("searchInput").value.toLowerCase();
    let rows = document.querySelectorAll(".stock-table tbody tr");

    rows.forEach(rows => {
        let product = rows.cells[0].textContent.toLowerCase();
        rows.style.display = product.includes(filter) ? "" : "none";
    });
}

document.getElementById("stockWariningBtn").addEventListener("click", function() {
    const rows = document.querySelectorAll(".stock-table tbody tr");
    const isActive = this.classList.toggle("active");

    if (isActive) {
        rows.forEach(row => {
            if (!row.classList.contains("low-stock")) {
                row.style.display = "none";
            } else {
                row.style.display = "";
            }
        });
        // Desactivar el otro botón y restablecer su filtro
        document.getElementById("stockAlertBtn").classList.remove("active");
    } else {
        rows.forEach(row => {
            row.style.display = "";
        });
    }
});

document.getElementById("stockAlertBtn").addEventListener("click", function() {
    const rows = document.querySelectorAll(".stock-table tbody tr");
    const isActive = this.classList.toggle("active");

    if (isActive) {
        rows.forEach(row => {
            if (!row.classList.contains("null-stock")) {
                row.style.display = "none";
            } else {
                row.style.display = "";
            }
        });
        // Desactivar el otro botón y restablecer su filtro
        document.getElementById("stockWariningBtn").classList.remove("active");
    } else {
        rows.forEach(row => {
            row.style.display = "";
        });
    }
});