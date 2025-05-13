let currentSlide = 0; 
// Índice del slide actual (comienza en 0)

const slides = document.querySelectorAll('.slide'); 
// Selecciona todos los elementos con la clase 'slide' y los guarda en un array

function showNextSlide() {
    slides[currentSlide].classList.remove('active'); 
    // Quita la clase 'active' del slide actual

    currentSlide = (currentSlide + 1) % slides.length; 
    // Actualiza el índice al siguiente slide. Si llega al final, vuelve al principio (gracias al módulo)

    slides[currentSlide].classList.add('active'); 
    // Añade la clase 'active' al nuevo slide actual
}

setInterval(showNextSlide, 3000); 
// Llama a la función showNextSlide cada 3 segundos para cambiar la imagen automáticamente
