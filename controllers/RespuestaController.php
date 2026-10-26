<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/PreguntaModel.php';
require_once __DIR__ . '/../models/RespuestaModel.php';

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

    // Comprobar que el usuario ha iniciado sesión
    if (!isset($_SESSION["id_usuario"])) {
        $this->redirect("index.php?controller=LoginController&accion=index");
        return;
    }

    // Comprobar que el formulario se ha enviado por POST
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        $this->redirect("index.php");
        return;
    }

    // Recoger los datos del formulario
    $idPregunta = filter_input(INPUT_POST, "id_pregunta", FILTER_VALIDATE_INT);
    $texto = trim($_POST["texto"] ?? "");
    $idUsuario = $_SESSION["id_usuario"];

    // Comprobar los datos obligatorios
    if (!$idPregunta || $texto === "") {
        $this->redirect("index.php");
        return;
    }

    // Comprobar si se ha adjuntado un archivo
    $ruta = null;
    $nombre = null;

    if (isset($_FILES["adjunto"]) &&
        $_FILES["adjunto"]["error"] !== UPLOAD_ERR_NO_FILE) {

        if ($_FILES["adjunto"]["error"] !== UPLOAD_ERR_OK) {
            $this->redirect(
                "index.php?controller=PreguntaController&accion=show&id=" . $idPregunta
            );
            return;
        }

        // Tipos de archivo permitidos
        $tiposPermitidos = [
            "application/pdf",
            "image/jpeg",
            "image/png",
            "application/msword",
            "application/vnd.openxmlformats-officedocument.wordprocessingml.document"
        ];

        $archivo = $_FILES["adjunto"];
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $tipoArchivo = $finfo->file($archivo["tmp_name"]);

        if (!in_array($tipoArchivo, $tiposPermitidos, true)
            || $archivo["size"] > 5 * 1024 * 1024) {

            $this->redirect(
                "index.php?controller=PreguntaController&accion=show&id=" . $idPregunta
            );
            return;
        }

        // Crear la carpeta de adjuntos si no existe
        $directorio = __DIR__ . "/../../assets/adjuntos/respuestas/";

        if (!is_dir($directorio)) {
            mkdir($directorio, 0755, true);
        }

        // Crear un nombre único para evitar sobrescribir archivos
        $extension = strtolower(
            pathinfo($archivo["name"], PATHINFO_EXTENSION)
        );

        $nombreArchivo = bin2hex(random_bytes(16)) . "." . $extension;

        if (!move_uploaded_file(
            $archivo["tmp_name"],
            $directorio . $nombreArchivo
        )) {
            $this->redirect(
                "index.php?controller=PreguntaController&accion=show&id=" . $idPregunta
            );
            return;
        }

        $ruta = "assets/adjuntos/respuestas/" . $nombreArchivo;
        $nombre = basename($archivo["name"]);
    }

    // Guardar la respuesta y, si existe, su adjunto
    RespuestaModel::store(
        $idPregunta,
        $idUsuario,
        $texto,
        $ruta,
        $nombre
    );

    // Volver al detalle de la pregunta
    $this->redirect(
        "index.php?controller=PreguntaController&accion=show&id=" . $idPregunta
    );
}

    
    public function destroy() {
        
    }
    
    public function destroyAll() {
        
    }
}