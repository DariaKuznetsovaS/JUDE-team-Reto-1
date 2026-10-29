const input = document.getElementById('nombreUsuario');
const mensaje = document.getElementById('msgUsuario');

input.addEventListener('input', function () {
    const nombre = input.value.trim();

    if (nombre === '') {
        mensaje.innerText = '';
        return;
    }

    fetch('index.php?controller=UsuarioController&accion=comprobarUsuario&nombreUsuario=' + encodeURIComponent(nombre))
        .then(respuesta => respuesta.json())
        .then(datos => {
            if (datos.disponible) {
                mensaje.innerText = 'Nombre disponible';
                mensaje.style.color = '#4ade80';
            } else {
                mensaje.innerText = 'Nombre no válido o en uso';
                mensaje.style.color = '#ff8080';
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

    fetch('index.php?controller=UsuarioController&accion=comprobarContrasenna', {
        method: 'POST',
        body: datos
    })
        .then(respuesta => respuesta.json())
        .then(datos => {
            if (datos.valida) {
                mensajeContrasenna.innerText = 'Contraseña válida';
                mensajeContrasenna.style.color = '#4ade80';
            } else {
                mensajeContrasenna.innerText = datos.mensaje;
                mensajeContrasenna.style.color = '#ff8080';
            }
        });
});