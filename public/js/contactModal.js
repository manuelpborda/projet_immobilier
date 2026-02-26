// public/js/contactModal.js

// Esta función muestra el modal
function abrirModalContacto() {
    const modal = document.getElementById('contactModal');
    if (modal) {
        modal.style.display = 'block';
    }
}

// Esta función lo oculta
function cerrarModalContacto() {
    const modal = document.getElementById('contactModal');
    if (modal) {
        modal.style.display = 'none';
    }
}

// Opcional: cerrar al hacer clic fuera del modal
window.addEventListener('click', function(event) {
    const modal = document.getElementById('contactModal');
    if (event.target === modal) {
        modal.style.display = 'none';
    }
});
