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
        fetch('../Controller/buyController.php?action=getTable')
            .then(response => response.text())
            .then(html => {
                document.querySelector('.stock-table tbody').innerHTML = html;
            })
            .catch(error => {
                console.error('Error recargando tabla:', error);
            });
    }


    const productSelect = document.getElementById("product");
    const priceInput = document.getElementById("priceBuy");

    function updatePrice() {
        const selectedOption = productSelect.options[productSelect.selectedIndex];
        const cost = parseFloat(selectedOption.getAttribute("data-price"));
        priceInput.value = cost ? cost : "";
    }

    $('#product').select2({
        placeholder: "Busca un producto...",
        allowClear: true
    });


    updatePrice();

    $('#product').on('select2:select', updatePrice); 

    $('#totalBuy').text('$0.00');
    $('#totalAmount').text('0');
    
    $('#addProductBtn').click(function() {
        var product = $('#product option:selected').text();
        var productId = $('#product').val();
        var amount = $('#amount').val();
        var price = $('#priceBuy').val();
        var totalPrice = amount * price;
        var totalBuy = parseFloat($('#totalBuy').text().replace('$', '')) || 0;
        totalBuy += totalPrice;
        $('#totalBuy').text(`$${totalBuy.toFixed(2)}`);

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
            $('#addBuyForm').append(hiddenInput);
        } else {
            alert('Por favor, complete todos los campos.');
        }
    });

    
    document.getElementById("addBuyForm").addEventListener("submit", function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        formData.append("action", "addBuy");
        fetch("../Controller/buyController.php", {
            method: "POST",
            body: formData,
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