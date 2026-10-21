<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/PreguntaModel.php';
require_once __DIR__ . '/../models/AjustesModel.php';

class AjustesController extends BaseController {
    
    public function ajustes() {
        require "views/ajustes.view.php";
        

    }
   
    public function editarDatos() {
        $apellidos=$_POST["apellidos"];
        $nombre=$_POST["nombre"];
        $nombreUsuario=$_POST["nombreUsuario"];
        $contrasenna=$_POST["contrasenna"];
        $usuario = $_SESSION['id_usuario'];
        $id = $usuario->id_usuario; 
        AjustesModel::editarUsuario($id, $nombre, $apellidos, $nombreUsuario, $contrasenna);
        
    }
    
    
    public function store() {

    }

    
    public function destroy() {
        
    }
    
    public function destroyAll() {
        
    }

    
}