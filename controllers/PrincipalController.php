<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/PreguntaModel.php';

class PrincipalController extends BaseController {
    
    public function index() {
        $preguntas=PreguntaModel::getAll();

       /*
       Para aprovechar el render, esto:
       require "views/index.view.php";
       se convierte en esto: */
       $this->render("index.view.php",[
        "preguntas"=>$preguntas
       ]);
         

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