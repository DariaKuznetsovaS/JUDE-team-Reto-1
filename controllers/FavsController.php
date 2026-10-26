<?php

require "BaseController.php";


class FavsController extends BaseController{

    public function index() {
        $respuestasFavoritas=FavsModel::getByIdUsuario();
         $user = UsuarioModel::getById($id);


        $this-> redirect(
            "views/favoritos.view.php"
        ) ;
         

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

?>

