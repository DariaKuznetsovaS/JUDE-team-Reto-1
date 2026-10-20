<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/PreguntaModel.php';
require_once __DIR__ . '/../models/RespuestaModel.php';

class PreguntaController extends BaseController {
    
    public function index() {
        $id=$_GET["id"];
        $pregunta=PreguntaModel::getById($id);
        $respuestas=RespuestaModel::getByPreguntaId($id);
        require "views/pregunta.view.php";
        

    }
   
    public function show() {
        
    }
    
    public function store() {
        
    }
    
    public function destroy() {
        
    }
    
    public function destroyAll() {
        
    }

    public function crear() {
    require "views/crearPregunta.view.php";
}
}