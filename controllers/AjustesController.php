<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/UsuarioModel.php';
require_once __DIR__ . '/../models/PreguntaModel.php';
require_once __DIR__ . '/../models/AjustesModel.php';
 
class AjustesController extends BaseController {
 
    public function ajustes() {
        $usuario = $_SESSION["id_usuario"];
        require "views/ajustes.view.php";
    }
 
    public function editarDatos() {
 
        $usuario = $_SESSION['id_usuario'];
        $id = $usuario->id_usuario;
 
        $apellidos     = $_POST["apellidos"] ?? '';
        $nombre        = $_POST["nombre"] ?? '';
        $nombreUsuario = $_POST["nombreUsuario"] ?? '';
        $contrasenna   = $_POST["contrasenna"] ?? '';
        $tipo          = $_POST["tipo"] ?? '';
 
        // Validaciones en el servidor (por si se salta el JS)
        $errores = [];
 
        // Nombre de usuario
        if ($nombreUsuario !== '' && $nombreUsuario !== $usuario->nombre_usuario) {
            $mensaje = $this->validarNombreUsuario($nombreUsuario, $id);
            if ($mensaje !== '') {
                $errores[] = "Nombre de usuario: " . $mensaje . ".";
            }
        }
 
        // Contraseña
        if ($contrasenna !== '') {
            $mensaje = $this->validarContrasenna($contrasenna);
            if ($mensaje !== '') {
                $errores[] = "Contraseña: " . $mensaje . ".";
            }
        }
 
        // Tipo: un admin que pasa a user no puede ser el último admin
        if ($usuario->tipo === 'admin' && $tipo === 'user') {
            $mensaje = $this->validarTipo($tipo, $id);
            if ($mensaje !== '') {
                $errores[] = "Tipo: " . $mensaje . ".";
            }
        }
 
        if (!empty($errores)) {
            $_SESSION['errores_ajustes'] = $errores;
            $this->redirect("index.php?controller=AjustesController&accion=ajustes");
        }
 
        // Si el campo viene vacío, se mantiene el dato actual
        if ($apellidos === '')     { $apellidos = $usuario->apellidos; }
        if ($nombre === '')        { $nombre = $usuario->nombre; }
        if ($nombreUsuario === '') { $nombreUsuario = $usuario->nombre_usuario; }
        if ($contrasenna === '')   { $contrasenna = $usuario->contrasenna; }
 
        // Tipo: solo lo puede cambiar un admin
        if ($usuario->tipo !== 'admin' || !in_array($tipo, ['admin', 'user'])) {
            $tipo = $usuario->tipo;
        }
 
        try {
            AjustesModel::editarUsuario($id, $nombre, $apellidos, $nombreUsuario, $contrasenna, $tipo);
        } catch (PDOException $e) {
            // Por ejemplo: intentar degradar al último admin (lo bloquea un trigger)
            $_SESSION['errores_ajustes'] = ["No se han podido guardar los cambios."];
            $this->redirect("index.php?controller=AjustesController&accion=ajustes");
        }
 
        // Refrescar la sesión con los datos nuevos
        $_SESSION['id_usuario'] = AjustesModel::obtenerPorId($id);
 
        $this->redirect("index.php?controller=AjustesController&accion=ajustes");
    }
 
    // Responde al fetch del nombre de usuario
    public function comprobarUsuario() {
        $nombre = $_GET['nombreUsuario'] ?? '';
        $id = $_SESSION['id_usuario']->id_usuario;
 
        $mensaje = $this->validarNombreUsuario($nombre, $id);
 
        header('Content-Type: application/json');
        echo json_encode(['disponible' => $mensaje === '', 'mensaje' => $mensaje]);
        exit;
    }
 
    // Responde al fetch de la contraseña
    public function comprobarContrasenna() {
        $contrasenna = $_POST['contrasenna'] ?? '';
        $mensaje = $this->validarContrasenna($contrasenna);
 
        header('Content-Type: application/json');
        echo json_encode(['valida' => $mensaje === '', 'mensaje' => $mensaje]);
        exit;
    }
 
    // Responde al fetch del tipo
    public function comprobarTipo() {
        $tipo = $_POST['tipo'] ?? '';
        $id = $_SESSION['id_usuario']->id_usuario;
 
        $mensaje = $this->validarTipo($tipo, $id);
 
        header('Content-Type: application/json');
        echo json_encode(['valida' => $mensaje === '', 'mensaje' => $mensaje]);
        exit;
    }
 
    private function validarNombreUsuario($nombre, $idActual) {
        if (strlen($nombre) < 3) {
            return 'Debe tener al menos 3 caracteres';
        }
        if (AjustesModel::existeNombreUsuario($nombre, $idActual)) {
            return 'Ese nombre de usuario ya está en uso';
        }
        return '';
    }
 
    // Devuelve '' si la contraseña es válida, o el mensaje de lo que falta
    private function validarContrasenna($contrasenna) {
        if (strlen($contrasenna) < 6) {
            return 'Debe tener al menos 6 caracteres';
        }
        if (!preg_match('/[a-z]/', $contrasenna)) {
            return 'Debe tener una minúscula';
        }
        if (!preg_match('/[A-Z]/', $contrasenna)) {
            return 'Debe tener una mayúscula';
        }
        if (!preg_match('/\d/', $contrasenna)) {
            return 'Debe tener un número';
        }
        return '';
    }
 
    private function validarTipo($tipo, $idActual) {
        if ($tipo === 'user' && !AjustesModel::hayOtroAdmin($idActual)) {
            return 'Es el último admin, no se puede cambiar a user';
        }
        return '';
    }
 
    public function store() {
 
    }
 
    public function destroy() {
 
    }
 
    public function destroyAll() {
 
    }
}