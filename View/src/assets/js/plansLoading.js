document.addEventListener("DOMContentLoaded", function () {

    reloadPlans();

    const notyf = new Notyf({
        duration: 3000,
        position: {
          x: 'right',
          y: 'bottom',
        },
        dismissible: true,
      });

    function reloadPlans(){
        fetch('../Controller/businessController.php?action=getPlans')
            .then(response => response.text())
            .then(html => {
                document.querySelector('.plans-content').innerHTML = html;
                selectorPlans();
            })
            .catch(error => {
                console.error('Error recargando tabla:', error);
            });
    }

    function selectorPlans() {
        const forms = document.querySelectorAll(".business-form-2");

        forms.forEach(form => {
            const checkbox = form.querySelector(".business-select--checkbox");

            form.addEventListener("click", function (event) {
                if (event.target !== checkbox) {
                    document.querySelectorAll(".business-select--checkbox").forEach(cb => cb.checked = false);
                    checkbox.checked = true;
                }
            });
        });
    }



    // Enviar el formulario con el plan seleccionado
    document.getElementById("submitSelectedPlan").addEventListener("click", function () {
        const forms = document.querySelectorAll(".business-form-2");
        let submitted = false;
    
        forms.forEach(form => {
            const checkbox = form.querySelector(".business-select--checkbox");
            if (checkbox.checked && !submitted) {
                submitted = true;
    
                const formData = new FormData(form);
                formData.append("action", "addPlans");
    
                for (let pair of formData.entries()) {
                    console.log(pair[0]+ ': ' + pair[1]);
                }

                fetch("../Controller/businessController.php", {
                    method: "POST",
                    body: formData,
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === "success") {
                        notyf.success(data.message);
                        setTimeout(() => {
                            window.location.href = data.redirect;
                        }, 2500);
                    } else {
                        notyf.error(data.message);
                    }
                })
                .catch(() => {
                    notyf.error("Error al enviar el plan.");
                });
            }
        });
    
        if (!submitted) {
            notyf.error("Por favor, selecciona un plan antes de enviar.");
        }
    });

    
});