document.addEventListener('DOMContentLoaded', () => {
    // Espera a que todo el DOM esté cargado antes de ejecutar el script

    // Selecciona todos los contenedores con la clase 'rating' (uno por alumno)
    document.querySelectorAll('.rating').forEach(ratingDiv => {
        const alumnoId = ratingDiv.dataset.alumnoId; 
        // Obtiene el ID del alumno desde el atributo data-alumno-id

        const stars = ratingDiv.querySelectorAll('.star'); 
        // Selecciona todas las estrellas dentro de este contenedor

        const textarea = document.querySelector(`.comentario[data-alumno-id="${alumnoId}"]`);
        // Selecciona el textarea del comentario correspondiente a este alumno

        let puntuacionActual = 0; 
        // Inicializa la puntuación actual

        // Establece visualmente qué estrellas están marcadas como activas (rellenas)
        stars.forEach((star, index) => {
            if (star.textContent === '★') puntuacionActual = index + 1;
        });

        // Si hay un textarea, añade evento cuando se pierde el foco (blur)
        if (textarea) {
            textarea.addEventListener('blur', () => {
                const comentario = encodeURIComponent(textarea.value);
                // Codifica el comentario para enviarlo en la URL

                // Envía el comentario al servidor mediante AJAX
                fetch('insertar_valoracion.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `id_alumno=${alumnoId}&comentario=${comentario}`
                })
                .then(response => response.json())
                .then(data => {
                    if (!data.success) {
                        alert('Error al guardar el comentario: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error en la petición AJAX:', error);
                    alert('Error de conexión al guardar el comentario');
                });
            });
        }

        // Añade eventos click a cada estrella
        stars.forEach((star, index) => {
            star.addEventListener('click', () => {
                const puntuacion = index + 1; 
                // La puntuación es el índice de la estrella clicada + 1

                puntuacionActual = puntuacion;

                const comentario = encodeURIComponent(textarea?.value || '');
                // Codifica el comentario actual, si existe

                // Envía la puntuación (y el comentario si hay) al servidor
                fetch('insertar_valoracion.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `id_alumno=${alumnoId}&puntuacion=${puntuacion}&comentario=${comentario}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Si la valoración se guarda correctamente, se actualizan las estrellas visualmente
                        actualizarEstrellas(stars, puntuacion);
                    } else {
                        alert('Error al guardar la valoración: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error en la petición AJAX:', error);
                    alert('Error de conexión al guardar la valoración');
                });
            });
        });
    });
});

// Función para actualizar visualmente las estrellas según la puntuación
function actualizarEstrellas(stars, puntuacion) {
    stars.forEach((star, i) => {
        star.textContent = i < puntuacion ? '★' : '☆';
    });
}
