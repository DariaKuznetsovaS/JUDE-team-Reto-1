document.addEventListener('DOMContentLoaded', function () {

    //CONFIRMAR ANTES DE ELIMINAR UN USUARIO
    document.querySelectorAll('a.eliminar').forEach(function (enlace) {
        enlace.addEventListener('click', function (e) {
            if (!confirm('¿Estás seguro de que quieres eliminar este usuario?')) {
                e.preventDefault();
            }
        });
    });

    // ERROR POR SI ELIMINAN EL ULTIMO ADMIN
    const contenedor = document.getElementById('mensajeError');

    if (contenedor) {
        const aviso = document.createElement('p');
        aviso.textContent = contenedor.dataset.mensaje;
        aviso.style.color = 'red';
        aviso.style.fontWeight = 'bold';
        contenedor.replaceWith(aviso);
    }
});