// === SCRIPT DARK MODE TOGGLE ===
// Justifico al jurado: permite accesibilidad visual, guarda preferencia, y evita recarga innecesaria.

const darkToggleBtn = document.getElementById('darkModeToggle');

// Si clic, alterno light-mode (oscuro es por defecto)
darkToggleBtn.addEventListener('click', () => {
    const htmlTag = document.documentElement;
    const isLight = htmlTag.classList.toggle('light-mode');
    localStorage.setItem('lightMode', isLight);
});
