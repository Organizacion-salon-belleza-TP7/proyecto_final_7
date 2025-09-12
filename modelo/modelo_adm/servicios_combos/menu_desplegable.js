function toggleSidebar() {
    let sidebar = document.getElementById("sidebar");
    let content = document.getElementById("content");
    let titulo = document.querySelector("h1"); // Busca el h1 de la página

    sidebar.classList.toggle("hidden");
    content.classList.toggle("expanded");

    // Verificar si tiene la clase "hidden"
    if (sidebar.classList.contains("hidden")) {
      titulo.classList.add("title"); // Agrega la clase al h1
    } else {
      titulo.classList.remove("title"); // Quita la clase al h1
    }
  }