document.addEventListener('click', async function (event) {

    const boton = event.target.closest('.botonFavorito[data-id-respuesta]');

    if (!boton) {
        return;
    }

    // Evitar varios clics mientras se procesa la petición.
    if (boton.disabled) {
        return;
    }

    boton.disabled = true;

    const datos = new FormData();

    datos.append('id_respuesta', boton.dataset.idRespuesta);

    try {

        const respuesta = await fetch(
            'index.php?controller=RespuestaController&accion=toggleFavorito',
            {
                method: 'POST',
                body: datos
            }
        );

        const resultado = await respuesta.json();

        if (!respuesta.ok) {
            throw new Error(
                resultado.error || 'No se ha podido actualizar el favorito.'
            );
        }

        // Actualizar el corazón.
        boton.classList.toggle('favoritoActivo', resultado.favorito);

        boton.textContent = resultado.favorito ? '♥' : '♡';

        boton.setAttribute(
            'aria-pressed',
            resultado.favorito ? 'true' : 'false'
        );

        // Actualizar el contador.
        const tarjeta = boton.closest('.respuestaCard');
        const contador = tarjeta.querySelector('.numeroFavoritos');

        contador.textContent = resultado.total;

    } catch (error) {

        alert(error.message);

    } finally {

        boton.disabled = false;
    }
});




 