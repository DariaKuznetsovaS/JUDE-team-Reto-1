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

    if (!isset($_SESSION["id_usuario"])) {
        header("Location: index.php?controller=usuario&accion=login");
        exit;
    }
    
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        header("Location: index.php");
        exit;
    }

    
    $titulo = $_POST["titulo"] ?? "";
    $descripcion = $_POST["descripcion"] ?? "";
    $tipo = $_POST["tipo"] ?? "";
    $idUsuario = $_SESSION["id_usuario"];

    if ($titulo === "" || $descripcion === "" || $tipo === "") {
        exit("Debes rellenar todos los campos.");
    }


    $idPregunta = PreguntaModel::create(
        $idUsuario,
        $titulo,
        $descripcion,
        $tipo
    );

    // Si se ha adjuntado un archivo
    if (
        isset($_FILES["adjunto"]) &&
        $_FILES["adjunto"]["error"] !== UPLOAD_ERR_NO_FILE
    ) {
        if ($_FILES["adjunto"]["error"] !== UPLOAD_ERR_OK) {
            exit("Se ha producido un error al subir el archivo.");
        }

        // Aquí se debe validar y guardar el archivo
        // y registrar su ruta mediante el modelo de adjuntos.
    }

    // Volver a la página principal
    header("Location: index.php");
    exit;
}

    
    public function destroy() {
        
    }
    
    public function destroyAll() {
        
    }

    public function crear() {
    require "views/crearPregunta.view.php";
}
}