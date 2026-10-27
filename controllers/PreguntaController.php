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
        $this->redirect("index.php?controller=UsuarioController&accion=login");
        return;
    }

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        $this->redirect("index.php");
        return;
    }

    $titulo = $_POST["titulo"] ?? "";
    $descripcion = $_POST["descripcion"] ?? "";
    $tipo = $_POST["tipo"] ?? "";

    $usuarioSesion = $_SESSION["id_usuario"];

    $idUsuario = is_object($usuarioSesion)
        ? $usuarioSesion->id_usuario
        : $usuarioSesion;

    if ($titulo === "" || $descripcion === "" || $tipo === "") {
        exit("Debes rellenar todos los campos.");
    }

    // Guardar la pregunta y obtener su ID.
    $idPregunta = PreguntaModel::create(
        $idUsuario,
        $titulo,
        $descripcion,
        $tipo
    );

    // Comprobar si se ha adjuntado una imagen.
    if (
        isset($_FILES["adjunto"]) &&
        $_FILES["adjunto"]["error"] !== UPLOAD_ERR_NO_FILE
    ) {
        if ($_FILES["adjunto"]["error"] !== UPLOAD_ERR_OK) {
            exit("Se ha producido un error al subir el archivo.");
        }

        // Comprobar que el archivo sea una imagen.
        if (getimagesize($_FILES["adjunto"]["tmp_name"]) === false) {
            exit("El archivo seleccionado no es una imagen válida.");
        }

        // Carpeta donde se guardarán las imágenes.
        $carpeta = __DIR__ . "/../assets/adjuntos/preguntas/";

        if (!is_dir($carpeta)) {
            mkdir($carpeta, 0775, true);
        }

        // Generar un nombre único para evitar sobrescribir imágenes.
        $extension = strtolower(
            pathinfo($_FILES["adjunto"]["name"], PATHINFO_EXTENSION)
        );

        $nombreArchivo = uniqid("pregunta_", true) . "." . $extension;
        $destino = $carpeta . $nombreArchivo;

        // Guardar físicamente la imagen.
        if (!move_uploaded_file($_FILES["adjunto"]["tmp_name"], $destino)) {
            exit("No se ha podido guardar la imagen.");
        }

        // Registrar la ruta de la imagen en la base de datos.
        $ruta = "assets/adjuntos/preguntas/" . $nombreArchivo;

        PreguntaModel::guardarAdjunto(
            $idPregunta,
            $ruta,
            $_FILES["adjunto"]["name"]
        );
    }

    // Volver a la página principal.
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
    
}