const input = document.getElementById('nombreUsuario');
const mensaje = document.getElementById('msgUsuario');

input.addEventListener('input', function () {
    const nombre = input.value.trim();

    if (nombre === '') {
        mensaje.innerText = '';
        return;
    }

    if (nombre.length < 3) {
        mensaje.innerText = 'Debe tener al menos 3 caracteres';
        mensaje.style.color = '#b3261e';
        return;
    }

    fetch('index.php?controller=AjustesController&accion=comprobarUsuario&nombreUsuario=' + encodeURIComponent(nombre))
        .then(respuesta => respuesta.json())
        .then(datos => {
            if (datos.disponible) {
                mensaje.innerText = 'Nombre disponible';
                mensaje.style.color = '#20B8A6';
            } else {
                mensaje.innerText = datos.mensaje;
                mensaje.style.color = '#b3261e';
            }
        });
});

const inputContrasenna = document.getElementById('contrasenna');
const mensajeContrasenna = document.getElementById('msgContrasenna');

inputContrasenna.addEventListener('input', function () {
    const contrasenna = inputContrasenna.value;

    if (contrasenna === '') {
        mensajeContrasenna.innerText = '';
        return;
    }

    const datos = new FormData();
    datos.append('contrasenna', contrasenna);

    fetch('index.php?controller=AjustesController&accion=comprobarContrasenna', {
        method: 'POST',
        body: datos
    })
        .then(respuesta => respuesta.json())
        .then(datos => {
            if (datos.valida) {
                mensajeContrasenna.innerText = 'Contraseña válida';
                mensajeContrasenna.style.color = '#20B8A6';
            } else {
                mensajeContrasenna.innerText = datos.mensaje;
                mensajeContrasenna.style.color = '#b3261e';
            }
        });
});