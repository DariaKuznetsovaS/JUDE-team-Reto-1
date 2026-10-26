<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/UsuarioModel.php';



class UsuarioController extends BaseController {

    public function index() {
        $id = $_GET["id"];
        $user = UsuarioModel::getById($id);
        $preguntasUsuario = UsuarioModel::getByIdUsuario($id);
        $usuarios = UsuarioModel::getAll();
        require "views/usuarios.view.php";
        
         

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

        $usuario=UsuarioModel::registrarse($nombreUsuario,$nombre, $apellidos, $contrasenna);
        $this->redirect("index.php?controller=PrincipalController&accion=index");
        
        
    }

    public function logout() {
        session_unset();
        $this->redirect("index.php?controller=PrincipalController&accion=index");
        
    }
    
    public function perfil() {
       $id = $_SESSION["id_usuario"];

        $usuario = UsuarioModel::getById($id);

        $this->render('perfil.view.php', [
            'usuario' => $usuario
        ]);

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

    


}

