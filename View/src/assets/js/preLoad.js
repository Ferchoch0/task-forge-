window.addEventListener("load", () => {
    setTimeout(() => {
        const preloader = document.getElementById("preloader");
        preloader.style.opacity = "0";
        preloader.style.transition = "opacity 0.5s ease";
    
        setTimeout(() => {
          preloader.style.display = "none";
    
          const content = document.getElementById("content");
          content.style.display = "block";
        }, 450);
      }, 500);
  });


