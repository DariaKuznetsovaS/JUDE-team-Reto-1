<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/LoginModel.php';



class LoginController extends BaseController {
    
    public function index() {
        require "views/login.php";
        
    }

    public function iniciarSesion(){
        echo "funcion";
        $contrasenna=$_POST["contrasenna"];
        $nombreUsuario=$_POST["nombreUsuario"];

        echo $contrasenna;
        $usuario = LoginModel::iniciarSesion($nombreUsuario, $contrasenna);
        $tipo = $usuario->tipo;
                
        if ($usuario) {
            session_start();
            $_SESSION['usuario'] = $usuario;
            
            
            header("Location: index.php?controller=RegistroController&accion=index&tipo=$tipo");
            exit();
        } else {
            echo "User invalido";

            
            header("Location: index.php?controller=LoginController&accion=index");
            exit();
        }
        echo $nombreUsuario;
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

