<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/PreguntaModel.php';

class PrincipalController extends BaseController {
    
    public function index() {
        $preguntas=PreguntaModel::getAll();
        require "views/index.view.php";
         

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