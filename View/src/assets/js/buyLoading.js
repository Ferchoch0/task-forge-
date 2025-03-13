document.addEventListener("DOMContentLoaded", function () {

    document.getElementById("addBuyForm").addEventListener("submit", function (e) {
        e.preventDefault();
        const formData = new FormData();
        formData.append("action", "add");
        formData.append("product_id", document.getElementById("product").value);
        formData.append("amount", document.getElementById("amount").value);
        formData.append("priceBuy", document.getElementById("priceBuy").value);
        formData.append("payment", document.getElementById("payment").value);


        fetch("../Controller/buyController.php", {
            method: "POST",
            body: formData,
        })
        .then(response => response.text())
        .then(data => {
            modal.style.display = "none";
            location.reload();
        });
    });
});