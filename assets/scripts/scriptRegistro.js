const input = document.getElementById('nombreUsuario');
const mensaje = document.getElementById('msgUsuario');
const inputContrasenna = document.getElementById('contrasenna');
const mensajeContrasenna = document.getElementById('msgContrasenna');

// El formulario se obtiene desde el propio input, así vale para registro y para crear usuario
const formulario = input.closest('form');
const botonGuardar = formulario.querySelector('input[type="submit"]');

// Aquí empiezan en false porque los campos son obligatorios
const estado = {
    usuario: false,
    contrasenna: false
};

function todoValido() {
    return estado.usuario && estado.contrasenna;
}

function actualizarBoton() {
    botonGuardar.disabled = !todoValido();
}

// Si se intenta enviar con errores (por ejemplo con Intro), se frena
formulario.addEventListener('submit', function (e) {
    if (!todoValido()) {
        e.preventDefault();
    }
});

actualizarBoton();

/* ---------- NOMBRE DE USUARIO ---------- */

input.addEventListener('input', function () {
    const nombre = input.value.trim();

    if (nombre === '') {
        mensaje.innerText = '';
        estado.usuario = false;
        actualizarBoton();
        return;
    }

    if (nombre.length < 3) {
        mensaje.innerText = 'Debe tener al menos 3 caracteres';
        mensaje.style.color = '#b3261e';
        estado.usuario = false;
        actualizarBoton();
        return;
    }

    // Mientras se comprueba, no se deja enviar
    estado.usuario = false;
    actualizarBoton();

    fetch('index.php?controller=UsuarioController&accion=comprobarUsuario&nombreUsuario=' + encodeURIComponent(nombre))
        .then(respuesta => respuesta.json())
        .then(datos => {
            if (input.value.trim() !== nombre) { return; } // llegó tarde, se ignora

            if (datos.disponible) {
                mensaje.innerText = 'Nombre disponible';
                mensaje.style.color = '#20B8A6';
                estado.usuario = true;
            } else {
                mensaje.innerText = datos.mensaje;
                mensaje.style.color = '#b3261e';
                estado.usuario = false;
            }
            actualizarBoton();
        })
        .catch(() => {
            mensaje.innerText = 'No se ha podido comprobar el nombre';
            mensaje.style.color = '#b3261e';
            estado.usuario = false;
            actualizarBoton();
        });
});

/* ---------- CONTRASEÑA ---------- */

inputContrasenna.addEventListener('input', function () {
    const contrasenna = inputContrasenna.value;

    if (contrasenna === '') {
        mensajeContrasenna.innerText = '';
        estado.contrasenna = false;
        actualizarBoton();
        return;
    }

    estado.contrasenna = false;
    actualizarBoton();

    const datos = new FormData();
    datos.append('contrasenna', contrasenna);

    fetch('index.php?controller=UsuarioController&accion=comprobarContrasenna', {
        method: 'POST',
        body: datos
    })
        .then(respuesta => respuesta.json())
        .then(datos => {
            if (inputContrasenna.value !== contrasenna) { return; }

            if (datos.valida) {
                mensajeContrasenna.innerText = 'Contraseña válida';
                mensajeContrasenna.style.color = '#20B8A6';
                estado.contrasenna = true;
            } else {
                mensajeContrasenna.innerText = datos.mensaje;
                mensajeContrasenna.style.color = '#b3261e';
                estado.contrasenna = false;
            }
            actualizarBoton();
        })
        .catch(() => {
            mensajeContrasenna.innerText = 'No se ha podido comprobar la contraseña';
            mensajeContrasenna.style.color = '#b3261e';
            estado.contrasenna = false;
            actualizarBoton();
        });
});