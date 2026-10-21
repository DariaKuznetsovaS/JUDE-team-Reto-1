<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/PreguntaModel.php';

class RespuestaController extends BaseController {
    
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
        if(!isset($_SESSION["usuario"])){
            $this->redirect("index.php?controller=LoginController$accion=index");
        }
        
    }
    
    public function destroy() {
        
    }
    
    public function destroyAll() {
        
    }
}