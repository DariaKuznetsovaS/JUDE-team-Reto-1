<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/UsuarioModel.php';
require_once __DIR__ . '/../models/RespuestaModel.php';
require_once __DIR__ . '/../models/PreguntaModel.php';



class UsuarioController extends BaseController {

    public function index() {
        $id = $_SESSION["id_usuario"]->id_usuario;
        $user = UsuarioModel::getById($id);
        $preguntasUsuario = UsuarioModel::getByIdUsuario($id);
        $usuarios = UsuarioModel::getAll();
        require "views/listaUsuarios.view.php";
        $preguntasUsuario = PreguntaModel::getByIdUsuario($id);
        
         

    }

    public function obtenerUsuarios() {

        $usuarios = UsuarioModel::getAll();
        $this->render("views/listaUsuarios.view.php", [
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
            
            
            $this->redirect("index.php?controller=PrincipalController&accion=index&tipo=$tipo");
            
        } else {

            
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
        $this->redirect("index.php?controller=PrincipalController&accion=index");
        
        
    }

    public function logout() {
        session_unset();
        $this->redirect("index.php?controller=PrincipalController&accion=index");
        
    }
    
    public function perfil() {
       $id = $_SESSION["id_usuario"]->id_usuario;

        $usuario = UsuarioModel::getById($id);

        $this->render('perfil.view.php', [
            'usuario' => $usuario
        ]);

    }

    public function misPreguntas(){
        $id=$_SESSION["id_usuario"]->id_usuario;
        $preguntas=[];
        $respuestas=[];
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


    public function editar() {
        $id = $_GET["id"] ?? null;

        if ($id === null) {
            return;
        }

        $user = UsuarioModel::getById($id);
        require "views/editarUser.view.php";
    }

    public function comprobarUsuario() {
        header('Content-Type: application/json');

        $nombreUsuario = trim($_GET['nombreUsuario'] ?? '');

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
    




}

