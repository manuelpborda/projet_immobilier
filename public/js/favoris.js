// =====================
// FAVORITOS AJAX SYMFONY
// =====================
// Este archivo JS gestiona la interacción del usuario con los botones de favoritos.
// Funciona en la vista de detalle de un inmueble y puede adaptarse a la página de favoritos del usuario.
// Toda la lógica es vía AJAX: nunca usamos localStorage, pues la información se guarda en la base de datos.

// ----- FUNCIONALIDAD 1: Agregar/Quitar favorito desde el botón del detalle de un inmueble -----
function toggleFavorito(id) {
    // Localizo el icono del corazón asociado al inmueble
    const icon = document.getElementById('icon-' + id);

    // Intento agregar el inmueble a favoritos
    fetch('/favorito/agregar/' + id, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Si la respuesta es exitosa, muestro el corazón rojo (agregado)
            icon.innerText = '❤️';
        } else if (data.message === 'Ya es favorito') {
            // Si ya era favorito, hago una petición para quitarlo
            fetch('/favorito/quitar/' + id, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(r => {
                if (r.success) icon.innerText = '🤍';
            });
        }
    });
}




