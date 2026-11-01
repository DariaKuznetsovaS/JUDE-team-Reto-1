const formulario = document.getElementById('formAjustes');
const botonGuardar = formulario.querySelector('input[type="submit"]');
 
// Estado de cada campo: true = se puede enviar
const estado = {
    usuario: true,
    contrasenna: true,
    tipo: true
};
 
function todoValido() {
    return estado.usuario && estado.contrasenna && estado.tipo;
}
 
// Activa o desactiva el botón según el estado de los campos
function actualizarBoton() {
    botonGuardar.disabled = !todoValido();
}
 
// Si se intenta enviar con errores (por ejemplo con Intro), se frena
formulario.addEventListener('submit', function (e) {
    if (!todoValido()) {
        e.preventDefault();
    }
});
 
/* ---------- NOMBRE DE USUARIO ---------- */
 
const input = document.getElementById('nombreUsuario');
const mensaje = document.getElementById('msgUsuario');
 
input.addEventListener('input', function () {
    const nombre = input.value.trim();
 
    if (nombre === '') {
        mensaje.innerText = '';
        estado.usuario = true;
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
 
    fetch('index.php?controller=AjustesController&accion=comprobarUsuario&nombreUsuario=' + encodeURIComponent(nombre))
        .then(respuesta => respuesta.json())
        .then(datos => {
            if (input.value.trim() !== nombre) { return; }   // llegó tarde, se ignora
 
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
 
const inputContrasenna = document.getElementById('contrasenna');
const mensajeContrasenna = document.getElementById('msgContrasenna');
 
inputContrasenna.addEventListener('input', function () {
    const contrasenna = inputContrasenna.value;
 
    if (contrasenna === '') {
        mensajeContrasenna.innerText = '';
        estado.contrasenna = true;
        actualizarBoton();
        return;
    }
 
    // Mientras se comprueba, no se deja enviar
    estado.contrasenna = false;
    actualizarBoton();
 
    const datos = new FormData();
    datos.append('contrasenna', contrasenna);
 
    fetch('index.php?controller=AjustesController&accion=comprobarContrasenna', {
        method: 'POST',
        body: datos
    })
        .then(respuesta => respuesta.json())
        .then(datos => {
            if (inputContrasenna.value !== contrasenna) { return; }   // llegó tarde, se ignora
 
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
 
/* ---------- TIPO (solo existe para admins) ---------- */
 
const inputTipo = document.getElementById('tipo');
const mensajeTipo = document.getElementById('msgTipo');
 
if (inputTipo) {
    inputTipo.addEventListener('change', function () {
        const tipo = inputTipo.value;
 
        if (tipo !== 'user') {
            mensajeTipo.innerText = '';
            estado.tipo = true;
            actualizarBoton();
            return;
        }
 
        // Mientras se comprueba, no se deja enviar
        estado.tipo = false;
        actualizarBoton();
 
        const datos = new FormData();
        datos.append('tipo', tipo);
 
        fetch('index.php?controller=AjustesController&accion=comprobarTipo', {
            method: 'POST',
            body: datos
        })
            .then(respuesta => respuesta.json())
            .then(datos => {
                if (inputTipo.value !== tipo) { return; }   // llegó tarde, se ignora
 
                if (datos.valida) {
                    mensajeTipo.innerText = 'Se puede cambiar a user';
                    mensajeTipo.style.color = '#20B8A6';
                    estado.tipo = true;
                } else {
                    mensajeTipo.innerText = datos.mensaje;
                    mensajeTipo.style.color = '#b3261e';
                    estado.tipo = false;
                }
                actualizarBoton();
            })
            .catch(() => {
                mensajeTipo.innerText = 'No se ha podido comprobar el tipo';
                mensajeTipo.style.color = '#b3261e';
                estado.tipo = false;
                actualizarBoton();
            });
    });
}