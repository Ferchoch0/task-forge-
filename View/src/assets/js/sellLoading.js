document.addEventListener("DOMContentLoaded", function () {

    const productSelect = document.getElementById("product");
    const priceInput = document.getElementById("priceSell");

    function updatePrice() {
        const selectedOption = productSelect.options[productSelect.selectedIndex];
        const price = selectedOption.getAttribute("data-price");
        priceInput.value = price ? price : "";
    }

    $('#product').select2({
        placeholder: "Busca un producto...",
        allowClear: true
    });


    updatePrice();
    $('#product').on('select2:select', updatePrice); 


    $('#totalSell').text('$0.00');
    $('#totalAmount').text('0');
    
    $('#addProductBtn').click(function() {
        var product = $('#product option:selected').text();
        var productId = $('#product').val();
        var amount = $('#amount').val();
        var price = $('#priceSell').val();
        var totalPrice = amount * price;
        var totalSell = parseFloat($('#totalSell').text().replace('$', '')) || 0;
        totalSell += totalPrice;
        $('#totalSell').text(`$${totalSell.toFixed(2)}`);

        var totalAmount = parseInt($('#totalAmount').text()) || 0;
        totalAmount += parseInt(amount);
        $('#totalAmount').text(totalAmount);

        if (productId && amount && price) {
            var newRow = `
                <tr>
                    <td>${product}</td>
                    <td>${amount}</td>
                    <td>$${totalPrice.toFixed(2)}</td>
                </tr>
            `;
            $('#changeTableBody').append(newRow);
            var hiddenInput = `<input type="hidden" name="products[]" value="${productId}|${amount}|${price}">`;
            $('#addSellForm').append(hiddenInput);
        } else {
            alert('Por favor, complete todos los campos.');
        }
    });


    const invoiceCheck = document.getElementById("invoice");
    const invoiceGroup = document.getElementById("invoiceGroup");

    function toggleInvoiceGroup() {
        if (invoiceCheck.checked) {
            invoiceGroup.classList.add("show");
            invoiceCheck.value = "on";
        } else {
            invoiceGroup.classList.remove("show");
            invoiceCheck.value = "off";
        }
    }

    // Llamar a la función al cargar la página
    toggleInvoiceGroup();

    // Agregar evento change al checkbox
    invoiceCheck.addEventListener("change", toggleInvoiceGroup);

    document.getElementById("addSellForm").addEventListener("submit", function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        formData.append("action", "add");
        fetch("../Controller/sellController.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Venta registrada con éxito');
                modal.style.display = "none";
                location.reload();
            } else {
                alert('Error al registrar la venta');
            }
        });
    });
});