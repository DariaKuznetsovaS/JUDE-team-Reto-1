<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/PreguntaModel.php';
require_once __DIR__ . '/../models/AjustesModel.php';

class AjustesController extends BaseController {
    
    public function ajustes() {
        require "views/ajustes.view.php";
        

    }
   
    public function editarDatos() {
        
        $usuario = $_SESSION['id_usuario'];
        $apellidos=$_POST["apellidos"]?? $usuario->apellidos;
        $nombre=$_POST["nombre"]?? $usuario->nombre;
        $nombreUsuario=$_POST["nombreUsuario"]?? $usuario->nombre_usuario;
        $contrasenna=$_POST["contrasenna"]?? $usuario->contrasenna;
        $id = $usuario->id_usuario; 
        AjustesModel::editarUsuario($id, $nombre, $apellidos, $nombreUsuario, $contrasenna);
        $this->redirect("index.php?controller=AjustesController&accion=ajustes");
    }
    
    
    public function store() {

    }

    
    public function destroy() {
        
    }
    
    public function destroyAll() {
        
    }

    
}