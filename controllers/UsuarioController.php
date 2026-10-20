<?php
require_once __DIR__ . '/BaseController.php';

class UsuarioController extends BaseController {

    public function index() {
        $user = UsuarioModel::getById();
        $preguntasUsuario = UsuarioModel::getByIdUsuario();
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