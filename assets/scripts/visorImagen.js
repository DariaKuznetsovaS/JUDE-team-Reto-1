const visor = document.getElementById('visorImagen');
const imagenAmpliada = document.getElementById('imagenAmpliada');
const botonCerrar = document.getElementById('cerrarVisor');

// Ampliar cualquier imagen de una pregunta o respuesta
document.querySelectorAll('.imagenPregunta, .imagenRespuesta').forEach(imagen => {
    imagen.addEventListener('click', () => {
        imagenAmpliada.src = imagen.src;
        imagenAmpliada.alt = imagen.alt;
        visor.classList.add('activo');
    });
});

// Cerrar con el botón
botonCerrar.addEventListener('click', () => {
    visor.classList.remove('activo');
    imagenAmpliada.src = '';
});

// Cerrar al pulsar fuera de la imagen
visor.addEventListener('click', evento => {
    if (evento.target === visor) {
        visor.classList.remove('activo');
        imagenAmpliada.src = '';
    }
});

// Cerrar con Escape
document.addEventListener('keydown', evento => {
    if (evento.key === 'Escape') {
        visor.classList.remove('activo');
        imagenAmpliada.src = '';
    }
});