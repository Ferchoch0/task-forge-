  document.addEventListener("DOMContentLoaded", function () {

    document.getElementById("addProductForm").addEventListener("submit", function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        formData.append("action", "add");

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
     const openEditModalBtns = document.querySelectorAll(".edit-button");
     const closeEditModalBtn = document.querySelector(".close-edit");
 
     openEditModalBtns.forEach(button => {
         button.addEventListener('click', (e) => {
             const productId = e.target.closest('button').getAttribute('data-id');
             const productName = e.target.closest('button').getAttribute('data-name');
             const productStock = e.target.closest('button').getAttribute('data-stock');
             const productMinStock = e.target.closest('button').getAttribute('data-min-stock');
             const productTypeAmount = e.target.closest('button').getAttribute('data-type-amount');
             const productPrice = e.target.closest('button').getAttribute('data-price');
 
             document.getElementById("editProductId").value = productId;
             document.getElementById("editProductName").value = productName;
             document.getElementById("editProductStock").value = productStock;
             document.getElementById("editProductMinStock").value = productMinStock;
             document.getElementById("editProductTypeAmount").value = productTypeAmount;
             document.getElementById("editProductPrice").value = productPrice;
 
             editModal.style.display = "flex";
             setTimeout(() => {
                 editModal.style.opacity = "1";
             }, 10); // Pequeño retraso para permitir que la transición ocurra
         });
     });
 
     if (closeEditModalBtn) {
         closeEditModalBtn.addEventListener("click", () => {
             editModal.style.opacity = "0";
             setTimeout(() => {
                 editModal.style.display = "none";
             }, 300); // Tiempo de la transición
         });
     }
 
     window.addEventListener("click", (event) => {
         if (event.target === editModal) {
             editModal.style.opacity = "0";
             setTimeout(() => {
                 editModal.style.display = "none";
             }, 300); // Tiempo de la transición
         }
     });
 
     // Enviar formulario de edición
     document.getElementById("editProductForm").addEventListener("submit", function (e) {
         e.preventDefault();
 
         const formData = new FormData(this);
         formData.append("action", "edit");
        
 
         fetch("../Controller/stockController.php", {
             method: "POST",
             body: formData
         })
         .then(response => response.text())
         .then(data => {
             editModal.style.opacity = "0";
             setTimeout(() => {
                 editModal.style.display = "none";
                 location.reload();
             }, 300); // Tiempo de la transición
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