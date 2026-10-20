<?php
require_once __DIR__ . '/BaseController.php';

class UsuarioController extends BaseController {

    public function index() {
        $id = $_GET["id"];
        $user = UsuarioModel::getById($id);
        $preguntasUsuario = UsuarioModel::getByIdUsuario($id);
        require "views/usuarios.view.php";
         

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