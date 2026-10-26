<?php

require "BaseController.php";


class FavsController extends BaseController{

    public function index() {
        $pregunta=PreguntaModel::getById();
        $respuestas=RespuestaModel::getAll();
        $this->render("pregunta.view.php",[
            "pregunta"=>$pregunta,
            "respuestas"=>$respuestas
        ]);
        //require "views/pregunta.view.php";
         

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

