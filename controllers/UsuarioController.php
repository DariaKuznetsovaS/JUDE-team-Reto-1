<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/UsuarioModel.php';
require_once __DIR__ . '/../models/PreguntaModel.php';
require_once __DIR__ . '/../models/RespuestaModel.php';



class UsuarioController extends BaseController {

    public function index() {
        $id = $_SESSION["id_usuario"]->id_usuario;
        $user = UsuarioModel::getById($id);
        $preguntasUsuario = UsuarioModel::getByIdUsuario($id);
        $usuarios = UsuarioModel::getAll();
        
        $preguntasUsuario = PreguntaModel::getByIdUsuario($id);
        
         

    }

    public function listarUsers() {

        $usuarios = UsuarioModel::getAll();
        $this->render("listaUsuarios.view.php", [
            'usuarios' => $usuarios
        ]);
        

    }
    
    public function login() {
        require "views/login.php";
        
    }

    public function iniciarSesion(){
        $contrasenna=$_POST["contrasenna"];
        $nombreUsuario=$_POST["nombreUsuario"];

        $usuario = UsuarioModel::iniciarSesion($nombreUsuario, $contrasenna);
        
                
        if ($usuario) {
            $_SESSION['id_usuario'] = $usuario;
            $tipo = $usuario->tipo;
            
            
            $this->redirect("index.php?controller=PrincipalController&accion=index");
            
        } else {

            
            $_SESSION['error_login'] = "Usuario o contraseña incorrectos.";
            $this->redirect("index.php?controller=UsuarioController&accion=login");
            
        }
    }


    public function registro() {
        require "views/registro.php";
    }

    public function registrarse() {
        $nombreUsuario=$_POST["nombreUsuario"];
        $nombre=$_POST["nombre"];
        $apellidos=$_POST["apellidos"];
        $contrasenna=$_POST["contrasenna"];

        if ($this->errorContrasenna($contrasenna) !== '') {
            $this->redirect("index.php?controller=UsuarioController&accion=registro");
            return;
        }

        $usuario=UsuarioModel::registrarse($nombreUsuario,$nombre, $apellidos, $contrasenna);
        if ($usuario) {
            
            
            $this->redirect("index.php?controller=UsuarioController&accion=login");
            
        } else {

            
            $this->redirect("index.php?controller=UsuarioController&accion=registro");
            
        }
        
        
    }

    public function logout() {
        session_unset();
        $this->redirect("index.php?controller=PrincipalController&accion=index");
        
    }
    
    public function perfil() {
       $id = $_SESSION["id_usuario"]->id_usuario;

        $usuario = UsuarioModel::getById($id);
        $preguntas = PreguntaModel::getByIdUsuario($id);
        $respuestas = RespuestaModel::getByIdUsuario($id);

        $this->render('perfil.view.php', [
        'usuario' => $usuario,
        'preguntas' => $preguntas,
        'respuestas' => $respuestas
        ]);

    }

    
public function verPerfil() {
    $id = $_GET['id'] ?? null;

    if (!$id || !is_numeric($id)) {
        $this->redirect("index.php");
        return;
    }

    $usuario = UsuarioModel::getById($id);

    if (!$usuario) {
        $this->redirect("index.php");
        return;
    }

    $preguntas = PreguntaModel::getByIdUsuario($id);
    $respuestas = RespuestaModel::getByIdUsuario($id);

    $this->render('perfil.view.php', [
        'usuario' => $usuario,
        'preguntas' => $preguntas,
        'respuestas' => $respuestas
    ]);
}

    public function misPreguntas(){
        $id=$_SESSION["id_usuario"]->id_usuario;

        $preguntas=PreguntaModel::preguntasPorId($id);
        $respuestas=RespuestaModel::respuestasPorId($id);
        
        require "views/misPreguntas.view.php";
    }



    public function store() {
        
    }
    
    public function destroy() {
        
    }
    
    public function destroyAll() {
        
    }


    public function prepararEditar() {
        $id = $_GET["id"] ?? null;

        if ($id === null) {
            return;
        }

        $usuarioAEditar = UsuarioModel::getById($id);
        $this-> render("editarAdmin.view.php", [
            'usuarioAEditar' => $usuarioAEditar
        ]) ;
    }

    public function actualizarUsuario() {
        $id = $_POST['id'] ?? '';

        if ($id === '' || !is_numeric($id)) {
            $this->redirect("index.php?controller=UsuarioController&accion=listarUsers");
            return;
        }

        $actual = UsuarioModel::getById($id);

        if (!$actual) {
            $this->redirect("index.php?controller=UsuarioController&accion=listarUsers");
            return;
        }

        $nuevoNombre     = $_POST['nuevoNombre'] ?? '';
        $nuevoApellido   = $_POST['nuevoApellido'] ?? '';
        $nuevoUsername   = $_POST['nuevoUsername'] ?? '';
        $nuevaContrasena = $_POST['nuevaContrasena'] ?? '';
        $tipo            = $_POST['tipo'] ?? '';

        $datos = [
            'nombre_usuario' => $nuevoUsername !== '' ? $nuevoUsername : $actual['nombre_usuario'],
            'contrasenna'    => $nuevaContrasena !== '' ? $nuevaContrasena : $actual['contrasenna'],
            'nombre'         => $nuevoNombre !== '' ? $nuevoNombre : $actual['nombre'],
            'apellidos'      => $nuevoApellido !== '' ? $nuevoApellido : $actual['apellidos'],
            'tipo'           => in_array($tipo, ['User', 'Admin'], true) ? $tipo : $actual['tipo']
        ];

        UsuarioModel::updateUsuario($id, $datos);

        $this->redirect("index.php?controller=UsuarioController&accion=listarUsers");
}
    


    public function comprobarUsuario() {
        header('Content-Type: application/json');

        $nombreUsuario = $_GET['nombreUsuario'] ?? '';

        if (!preg_match('/^[a-zA-Z0-9_]{3,20}$/', $nombreUsuario)) {
            echo json_encode(['disponible' => false]);
            return;
        }

        
        $existe = UsuarioModel::existeNombreUsuario($nombreUsuario);

        echo json_encode(['disponible' => !$existe]);
        exit();
    }

    private function errorContrasenna($contrasenna) {
        if (strlen($contrasenna) < 6) return 'Mínimo 6 caracteres';
        if (!preg_match('/[a-z]/', $contrasenna)) return 'Falta una minúscula';
        if (!preg_match('/[A-Z]/', $contrasenna)) return 'Falta una mayúscula';
        if (!preg_match('/[0-9]/', $contrasenna)) return 'Falta un número';
        return '';
    }

    public function comprobarContrasenna() {
        header('Content-Type: application/json');

        $contrasenna = $_POST['contrasenna'] ?? '';
        $error = $this->errorContrasenna($contrasenna);

        echo json_encode(['valida' => $error === '', 'mensaje' => $error]);
        exit();
    }
    

    public function crearUser() {
        require "views/nuevoUserAdmin.view.php";

    }

    public function annadirUsuarioNuevo(){
        $nombre=$_POST["nombre"];
        $apellidos=$_POST["apellidos"];
        $nombreUsuario=$_POST["nombreUsuario"];
        $contrasenna=$_POST["contrasenna"];
        $tipo=$_POST["tipo"];

        $usuario=UsuarioModel::crearUser($nombreUsuario,$nombre, $apellidos, $contrasenna, $tipo);
        $this->redirect("index.php?controller=UsuarioController&accion=crearUser");

    }




    public function eliminar() {

        $id = $_GET["id"] ?? null;

        if ($id === null) {
            $this->redirect("index.php?controller=UsuarioController&accion=listarUsers");
            return;
        }
        
        try{

            $usuario = UsuarioModel::getById($id);
            if ($usuario && $usuario['tipo'] === 'admin' && UsuarioModel::contarAdmins() <= 1){
                $_SESSION['error'] = "No se puede eliminar al último administrador.";
            } else {

                UsuarioModel::deleteUsuarioById($id);
            } 
            
        }catch (PDOException $e) {

                $_SESSION['error'] = "No se pudo eliminar el usuario.";

            }

        
        
        $this->redirect("index.php?controller=UsuarioController&accion=listarUsers");
    }

}
