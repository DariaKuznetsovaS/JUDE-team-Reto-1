<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/LoginModel.php';



class RegistroController extends BaseController {
    
    public function index() {
        require "assets"
        require "views/registro.php";
        
    }

    public function iniciarSesion(){
        echo "funcion";
        $contrasenna=$_POST["contrasenna"];
        $nombreUsuario=$_POST["nombreUsuario"];
        
        
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

