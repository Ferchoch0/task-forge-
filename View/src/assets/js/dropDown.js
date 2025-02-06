document.querySelector('.value-toggle').addEventListener('click', function () {
      const dropdown = this.parentElement;
      dropdown.classList.toggle('active');
    });

    // Cierra el menú si se hace clic fuera de él
    window.addEventListener('click', function (e) {
      const dropdown = document.querySelector('.input');
      if (!dropdown.contains(e.target)) {
        dropdown.classList.remove('active');
      }
    });