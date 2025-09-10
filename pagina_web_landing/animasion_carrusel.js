// Esperar a que el DOM esté listo (o usa <script defer>)
document.addEventListener('DOMContentLoaded', () => {
  const imagenes = [
    { url: "imagenes/log.jpg", nombre: "Productos a calidad precio", descripcion: "Los mejores servicios a calidad-precio en un solo lugar todos nuestros cliente siempre regresan que ¿esperas para venir?" },
    { url: "imagenes/istockphoto-2149593400-612x612.jpg", nombre: "¿Estas en duda?", descripcion: "Si no sabes que elegir puedes consultar los servicios,productos o combos que te interesan si aun no quedas satisfecho contactate con nosotros" },
    { url: "imagenes/imagenes_productos/aceite_eucaliptus.jpg", nombre: "Aceite de eucaliptos", descripcion: "Aceite de eucalipto para un masaje fresco y relajante" },
    { url: "imagenes/imagenes_productos/images_crema_hidratante.jpg", nombre: "Crema Hidratante", descripcion: "Crema hidratante para poder revivir tu piel manteniendola firme y suave" }
  ];

  const btnAtras = document.getElementById('atras');      // <img id="atras"> (ok)
  const btnAdelante = document.getElementById('adelante'); // <div id="adelante"> (ok)
  const contImg = document.getElementById('img');
  const contTexto = document.getElementById('texto');
  const contPuntos = document.getElementById('puntos');

  let actual = 0;

  function render() {
    // Imagen + texto
    contImg.innerHTML = `<img class="img" src="${imagenes[actual].url}" alt="${imagenes[actual].nombre}" loading="lazy">`;
    contTexto.innerHTML = `<h3>${imagenes[actual].nombre}</h3><p>${imagenes[actual].descripcion}</p>`;

    // Puntos (usa <span> y cierra bien)
    contPuntos.innerHTML = imagenes
      .map((_, i) => `<span class="${i === actual ? 'bold' : ''}"></span>`)
      .join('');
  }

  btnAtras.addEventListener('click', () => {
    actual = (actual - 1 + imagenes.length) % imagenes.length;
    render();
  });

  btnAdelante.addEventListener('click', () => {
    actual = (actual + 1) % imagenes.length;
    render();
  });

  // Inicializa
  render();

  // (Opcional) auto-avance
  // setInterval(() => { btnAdelante.click(); }, 3000);
});
