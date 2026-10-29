
const enlaceNormas = document.getElementById("abrirNormas");
const modalNormas = document.getElementById("modalNormas");
const cerrarNormas = document.getElementById("cerrarNormas");
const botonCerrarNormas = document.getElementById("botonCerrarNormas");

// Comprobar que los elementos existen antes de añadir eventos
if (enlaceNormas && modalNormas && cerrarNormas && botonCerrarNormas) {

    enlaceNormas.addEventListener("click", function (event) {
        event.preventDefault();
        modalNormas.hidden = false;
        cerrarNormas.focus();
    });

    function cerrarModal() {
        modalNormas.hidden = true;
        enlaceNormas.focus();
    }

    cerrarNormas.addEventListener("click", cerrarModal);
    botonCerrarNormas.addEventListener("click", cerrarModal);

    modalNormas.addEventListener("click", function (event) {
        if (event.target === modalNormas) {
            cerrarModal();
        }
    });

    document.addEventListener("keydown", function (event) {
        if (event.key === "Escape" && !modalNormas.hidden) {
            cerrarModal();
        }
    });
}


//VER MÁS:


document.addEventListener("DOMContentLoaded", function () {

    const preguntas = document.querySelectorAll(".listaPreguntas .enlacePregunta");
    const botonVerMas = document.getElementById("verMasPreguntas");

    const cantidadInicial = 5;
    const cantidadPorCarga = 5;

    let preguntasVisibles = cantidadInicial;

    if (!botonVerMas) {
        return;
    }

    // Ocultar todas las preguntas a partir de la sexta
    preguntas.forEach(function (pregunta, indice) {
        pregunta.hidden = indice >= cantidadInicial;
    });

    // Mostrar el botón solo si hay más de 5 preguntas
    botonVerMas.hidden = preguntas.length <= cantidadInicial;

    botonVerMas.addEventListener("click", function () {

        const nuevoLimite = Math.min(
            preguntasVisibles + cantidadPorCarga,
            preguntas.length
        );

        for (let i = preguntasVisibles; i < nuevoLimite; i++) {
            preguntas[i].hidden = false;
        }

        preguntasVisibles = nuevoLimite;

        // Ocultar el botón cuando ya no queden preguntas
        botonVerMas.hidden = preguntasVisibles >= preguntas.length;
    });

});


