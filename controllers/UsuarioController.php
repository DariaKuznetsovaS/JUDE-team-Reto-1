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

    public function editar() {
    $id = $_GET["id"] ?? null;

    if ($id === null) {
        return;
    }

    $user = UsuarioModel::getById($id);
    require "views/editarUser.view.php";
}

    public function logout() {
        session_start();
        session_destroy();

        header("Location: index.php");
        exit;
    }


}