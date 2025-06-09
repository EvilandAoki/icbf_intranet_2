document.addEventListener("DOMContentLoaded", function () {
  const carousel = document.querySelector('.carousel.slide');

  if (!carousel) return;

  // Crear el contenedor carousel-inner
  const carouselInner = document.createElement('div');
  carouselInner.classList.add('carousel-inner');

  // Obtener todos los elementos .field__item
  const items = carousel.querySelectorAll('.field__item');

  items.forEach((item, index) => {
    const carouselItem = document.createElement('div');
    carouselItem.classList.add('carousel-item');
    if (index === 0) {
      carouselItem.classList.add('active'); // Solo el primero debe estar activo
    }

    // Mover el contenido del .field__item dentro del nuevo .carousel-item
    while (item.firstChild) {
      carouselItem.appendChild(item.firstChild);
    }

    carouselInner.appendChild(carouselItem);
    item.remove(); // Eliminar el original
  });

  // Insertar el nuevo .carousel-inner en el contenedor
  carousel.appendChild(carouselInner);

  // Verificar cuántos carousel-item hay
  const totalItems = carouselInner.querySelectorAll('.carousel-item').length;

  // Agregar controles solo si hay más de un item
  if (totalItems > 1) {
    const prevControl = document.createElement('button');
    prevControl.className = 'carousel-control-prev';
    prevControl.type = 'button';
    prevControl.setAttribute('data-bs-target', '.carousel.slide');
    prevControl.setAttribute('data-bs-slide', 'prev');
    prevControl.innerHTML = `
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Previous</span>
    `;

    const nextControl = document.createElement('button');
    nextControl.className = 'carousel-control-next';
    nextControl.type = 'button';
    nextControl.setAttribute('data-bs-target', '.carousel.slide');
    nextControl.setAttribute('data-bs-slide', 'next');
    nextControl.innerHTML = `
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Next</span>
    `;

    carousel.appendChild(prevControl);
    carousel.appendChild(nextControl);
  }

  // Inicializar el carrusel
  const bsCarousel = new bootstrap.Carousel(carousel);
});
