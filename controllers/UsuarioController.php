<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/UsuarioModel.php';



class UsuarioController extends BaseController {
    
    public function login() {
        require "views/login.php";
        
    }

    public function iniciarSesion(){
        $contrasenna=$_POST["contrasenna"];
        $nombreUsuario=$_POST["nombreUsuario"];

        $usuario = UsuarioModel::iniciarSesion($nombreUsuario, $contrasenna);
        $tipo = $usuario->tipo;
                
        if ($usuario) {
            session_start();
            $_SESSION['id_usuario'] = $usuario;
            
            
            $this->redirect("index.php?controller=PrincipalController&accion=index&tipo=$tipo");
            
        } else {
            echo "User invalido";

            
            $this->redirect("index.php?controller=LoginController&accion=index");
            
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
    
    public function show() {
        
    }


    public function store() {
        
    }
    
    public function destroy() {
        
    }
    
    public function destroyAll() {
        
    }
}

