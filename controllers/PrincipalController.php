<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/PreguntaModel.php';

class PrincipalController extends BaseController {
    
    
    
    public function index() {
        $buscar = trim($_GET["buscar"] ?? "");
        $categoria = $_GET["categoria"] ?? "";

        $preguntas = PreguntaModel::filtrar($buscar, $categoria);

        $this->render("index.view.php", [
            "preguntas" => $preguntas
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