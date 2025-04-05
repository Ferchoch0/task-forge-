document.addEventListener("DOMContentLoaded", function () {

    reloadCategories();

    function reloadCategories(){
        fetch('../Controller/businessController.php?action=getCategories')
            .then(response => response.text())
            .then(html => {
                document.querySelector('.category-client').innerHTML = html;
            })
            .catch(error => {
                console.error('Error recargando tabla:', error);
            });
    }

    const notyf = new Notyf({
        duration: 3000,
        position: {
          x: 'right',
          y: 'bottom',
        },
        dismissible: true,
      });

    document.getElementById("addBusinessForm").addEventListener("submit", function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        formData.append("action", "addBusiness");


        fetch("../Controller/BusinessController.php", {
            method: "POST",
            body: formData,
        })
        .then(response => response.json())
        .then(data => {

            if (data.status === "success") {
                notyf.success(data.message);
                setTimeout(()=>{
                    window.location.href = data.redirect;
                }, 2500)
            } else {
                notyf.error(data.message);
            }
        });
    });

});