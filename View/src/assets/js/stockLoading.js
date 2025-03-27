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

    // RECARGA DE TABLA
    function reloadTable() {
        fetch('../Controller/stockController.php?action=getTable')
            .then(response => response.text())
            .then(html => {
                document.querySelector('.stock-table tbody').innerHTML = html;
                attachEditDeleteEvents();

            })
            .catch(error => {
                console.error('Error recargando tabla:', error);
            });
    }


    // MODAL DE AGREGAR PRODUCTOS
    document.getElementById("addProductForm").addEventListener("submit", function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        formData.append("action", "add");

        fetch("../Controller/stockController.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            reloadTable();
            document.getElementById("addProductForm").reset();
            document.getElementById("addModal").style.display = "none";
            if (data.status === "success") {
                notyf.success(data.message);
            } else {
                notyf.error(data.message);
            }
        });
    });


    
// ENVIAR EDICION DE PRODUCTOS
const openEditModalBtns = document.querySelectorAll(".edit-button");
const editModal = document.getElementById("editProductModal");
const closeEditModalBtn = document.querySelector(".close-edit");

document.getElementById("editProductForm").addEventListener("submit", function (e) {
    e.preventDefault();

    const formData = new FormData(this);
    formData.append("action", "edit");
   

    fetch("../Controller/stockController.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        editModal.style.opacity = "0";
        setTimeout(() => {
            editModal.style.display = "none";
            reloadTable();
            if (data.status === "success") {
                notyf.success(data.message);
            } else {
                notyf.error(data.message);
            }
        }, 300); // Tiempo de la transición
    });
});

    // FUNCION DE BOTONES
    function attachEditDeleteEvents() {
        // EDICION DE PRODUCTOS

        
    const openEditModalBtns = document.querySelectorAll(".edit-button");
    const editModal = document.getElementById("editProductModal");
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
                }, 10);
            });
        });

        if (closeEditModalBtn) {
            closeEditModalBtn.addEventListener("click", () => {
                editModal.style.opacity = "0";
                setTimeout(() => {
                    editModal.style.display = "none";
                }, 300);
            });
        }

        window.addEventListener("click", (event) => {
            if (event.target === editModal) {
                editModal.style.opacity = "0";
                setTimeout(() => {
                    editModal.style.display = "none";
                }, 300);
            }
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
                    .then(response => response.json())
                    .then(data => {
                        alert(data);
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