  document.addEventListener("DOMContentLoaded", function () {
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