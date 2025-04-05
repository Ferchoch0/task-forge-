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
                attachDeleteEvents();
            })
            .catch(error => {
                console.error('Error recargando tabla:', error);
            });
    }

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
            document.getElementById("addBuyForm").reset();
            document.getElementById("addModal").style.display = "none";
            reloadTable();
            document.getElementById("changeTableBody").innerHTML = '';
            $('#totalBuy').text('$0.00');
            $('#totalAmount').text('0');
            if (data.status === "success") {
                notyf.success(data.message);
            } else {
                notyf.error(data.message);
            }
        });
    });

    function attachDeleteEvents() {
        // ELIMINACION DE PRODUCTOS
        document.querySelectorAll('.delete-button').forEach(button => {
            button.addEventListener('click', (e) => {
                const productId = e.target.closest('button').getAttribute('data-id');
                
                if (confirm("¿Estás seguro de que quieres eliminar esta compra?")) {
                    // Llamar a la función para eliminar el producto mediante AJAX
                    fetch("../Controller/buyController.php", {
                        method: "POST",
                        body: JSON.stringify({ action: 'delete', id: productId }),
                        headers: { "Content-Type": "application/json" },
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
                }
            });
        });
    }
});