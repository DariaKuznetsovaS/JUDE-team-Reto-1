<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/PreguntaModel.php';
require_once __DIR__ . '/../models/RespuestaModel.php';

class PreguntaController extends BaseController {
    
    public function index() {

        $id = $_GET["id"] ?? null;

        if (!$id) {
            $this->redirect("index.php");
            return;
        }

        $pregunta = PreguntaModel::getById($id);

        // Obtener el usuario de la sesión.
        $usuarioSesion = $_SESSION["id_usuario"] ?? null;

        if (is_object($usuarioSesion)) {
            $idUsuario = $usuarioSesion->id_usuario ?? 0;
        } else {
            $idUsuario = $usuarioSesion ?? 0;
        }

        // Recuperar las respuestas con sus favoritos.
        $respuestas = RespuestaModel::getByPreguntaId(
            $id,
            (int) $idUsuario
        );

        $this->render('pregunta.view.php', [
            'pregunta' => $pregunta,
            'respuestas' => $respuestas
        ]);
    }


    public function show() {
        
    }
    
    
    public function store() {

    if (!isset($_SESSION["id_usuario"])) {
        //This porque hereda el método de base controller
        $this->redirect("index.php?controller=UsuarioController&accion=login");
    }
    
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        $this->redirect("index.php");
        
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
    $this->redirect("index.php");
   
}

    
    public function crear() {
    if (!isset($_SESSION['id_usuario'])) {
        $this->redirect("index.php?controller=UsuarioController&accion=login");
        return;
    }

    $this->render('formularioPregunta.view.php');
    }
    
    public function destroyAll() {
        
    } 
}