
const enlaceNormas = document.getElementById("abrirNormas");
const modalNormas = document.getElementById("modalNormas");
const cerrarNormas = document.getElementById("cerrarNormas");
const botonCerrarNormas = document.getElementById("botonCerrarNormas");

// Abrir la ventana al pulsar el enlace del footer
enlaceNormas.addEventListener("click", function (event) {
    event.preventDefault();
    modalNormas.hidden = false;
    cerrarNormas.focus();
});

// Función para cerrar la ventana
function cerrarModal() {
    modalNormas.hidden = true;
    enlaceNormas.focus();
}

// Cerrar con la X
cerrarNormas.addEventListener("click", cerrarModal);

// Cerrar con el botón inferior
botonCerrarNormas.addEventListener("click", cerrarModal);

// Cerrar al pulsar fuera del contenido
modalNormas.addEventListener("click", function (event) {
    if (event.target === modalNormas) {
        cerrarModal();
    }
});

// Cerrar al pulsar Escape
document.addEventListener("keydown", function (event) {
    if (event.key === "Escape" && !modalNormas.hidden) {
        cerrarModal();
    }
});
