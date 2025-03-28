document.addEventListener("DOMContentLoaded", function () {

    reloadClient();
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
        fetch('../Controller/sellController.php?action=getTable')
            .then(response => response.text())
            .then(html => {
                document.querySelector('.stock-table tbody').innerHTML = html;
            })
            .catch(error => {
                console.error('Error recargando tabla:', error);
            });
    }

    function reloadClient(){
        fetch('../Controller/sellController.php?action=getClients')
            .then(response => response.text())
            .then(html => {
                document.querySelector('.select-client').innerHTML = html;
            })
            .catch(error => {
                console.error('Error recargando tabla:', error);
            });
    }

    const productSelect = document.getElementById("product");
    const priceInput = document.getElementById("priceSell");

    function updatePrice() {
        console.log("Ejecutando updatePrice...");
        const margin = 0.3;
        const iva = 0.21;
        const selectedOption = productSelect.options[productSelect.selectedIndex];
        const cost = parseFloat(selectedOption.getAttribute("data-price"));
        const price =  Math.ceil(cost * (1 + margin) * (1 + iva));
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
    const paymentMethod = document.getElementById("payment");

    function toggleInvoiceGroup() {
        if (invoiceCheck.checked) {
            invoiceGroup.classList.add("show");
            invoiceCheck.value = "on";
            paymentMethod.querySelector("option[value='deuda']").disabled = false;
        } else {
            invoiceGroup.classList.remove("show");
            invoiceCheck.value = "off";
            paymentMethod.querySelector("option[value='deuda']").disabled = true;
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
            reloadTable();
            
            if (data.status === "success") {
                notyf.success(data.message);
            } else {
                notyf.error(data.message);
            }
        });
    });
});