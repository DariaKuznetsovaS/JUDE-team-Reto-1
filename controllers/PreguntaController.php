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
    
    public function crear() {
        if (!isset($_SESSION["id_usuario"])) {
            $this->redirect("index.php?controller=UsuarioController&accion=login");
        }

        $this->render('formularioPregunta.view.php');
    }

    public function editar() {

        // Comprobar que hay un usuario conectado.
        if (!isset($_SESSION['id_usuario'])) {
            $this->redirect("index.php?controller=UsuarioController&accion=login");
            return;
        }

        // Obtener el ID de la pregunta.
        $id = $_GET['id'] ?? null;

        if (!is_numeric($id) || $id < 1) {
            $this->redirect("index.php");
            return;
        }

        // Obtener la pregunta.
        $pregunta = PreguntaModel::getById($id);

        if (!$pregunta) {
            $this->redirect("index.php");
            return;
        }

        // Obtener el usuario conectado.
        $usuario = $_SESSION['id_usuario'];
        $idUsuario = $usuario->id_usuario;
        $tipoUsuario = $usuario->tipo;

        // Comprobar si es el autor o un administrador.
        $esAutor = (int) $idUsuario === (int) $pregunta['id_usuario'];
        $esAdmin = $tipoUsuario === 'admin';

        if (!$esAutor && !$esAdmin) {
            exit("No tienes permiso para editar esta pregunta.");
        }

        // Mostrar el formulario con los datos actuales.
        $this->render('editarPregunta.view.php', [
            'pregunta' => $pregunta
        ]);
    }


    
public function actualizar() {

    // Comprobar que el usuario ha iniciado sesión
    if (!isset($_SESSION["id_usuario"])) {
        $this->redirect("index.php?controller=UsuarioController&accion=login");
        return;
    }

    // Comprobar que el formulario se ha enviado por POST
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        $this->redirect("index.php");
        return;
    }

    $id = $_POST["id_pregunta"] ?? null;

    if (!$id) {
        $this->redirect("index.php");
        return;
    }

    // Obtener la pregunta
    $pregunta = PreguntaModel::getById($id);

    if (!$pregunta) {
        $this->redirect("index.php");
        return;
    }

    // Obtener el usuario de la sesión
    $usuarioSesion = $_SESSION["id_usuario"];

    $idUsuario = is_object($usuarioSesion)
        ? $usuarioSesion->id_usuario
        : $usuarioSesion;

    $tipoUsuario = is_object($usuarioSesion)
        ? ($usuarioSesion->tipo ?? "")
        : "";

    // Solo puede editar el autor o un administrador
    if (
        (int) $idUsuario !== (int) $pregunta["id_usuario"]
        && $tipoUsuario !== "admin"
    ) {
        $this->redirect("index.php");
        return;
    }

    // Recoger los datos del formulario
    $titulo = trim($_POST["titulo"] ?? "");
    $descripcion = trim($_POST["descripcion"] ?? "");
    $tipo = $_POST["tipo"] ?? "";

    $tiposPermitidos = [
        "aerodinamica",
        "materiales",
        "motores",
        "software",
        "procesos",
        "diseño",
        "otros"
    ];

    if (
        $titulo === ""
        || $descripcion === ""
        || !in_array($tipo, $tiposPermitidos, true)
    ) {
        exit("Debes rellenar correctamente todos los campos.");
    }

    // Actualizar los datos de la pregunta
    PreguntaModel::update(
        $id,
        $titulo,
        $descripcion,
        $tipo
    );

    // Procesar una imagen solo si se ha seleccionado otra
    if (
        isset($_FILES["adjunto"])
        && $_FILES["adjunto"]["error"] !== UPLOAD_ERR_NO_FILE
    ) {
        if ($_FILES["adjunto"]["error"] !== UPLOAD_ERR_OK) {
            exit("Se ha producido un error al subir la imagen.");
        }

        // Comprobar que el archivo es una imagen válida
        if (getimagesize($_FILES["adjunto"]["tmp_name"]) === false) {
            exit("El archivo seleccionado no es una imagen válida.");
        }

        $carpeta = __DIR__ . "/../assets/adjuntos/preguntas/";

        if (!is_dir($carpeta)) {
            mkdir($carpeta, 0775, true);
        }

        $extension = strtolower(
            pathinfo($_FILES["adjunto"]["name"], PATHINFO_EXTENSION)
        );

        $nombreArchivo = uniqid("pregunta_", true) . "." . $extension;
        $destino = $carpeta . $nombreArchivo;

        if (!move_uploaded_file($_FILES["adjunto"]["tmp_name"], $destino)) {
            exit("No se ha podido guardar la imagen.");
        }

        $ruta = "assets/adjuntos/preguntas/" . $nombreArchivo;

        // Sustituir las referencias de las imágenes anteriores
        $rutasAnteriores = PreguntaModel::reemplazarAdjuntoPregunta(
            $id,
            $ruta,
            $_FILES["adjunto"]["name"]
        );

        // Eliminar los archivos antiguos que ya no se utilizan
        foreach ($rutasAnteriores as $rutaAnterior) {
            $archivoAnterior = __DIR__ . "/../" . $rutaAnterior;

            if (
                is_file($archivoAnterior)
                && strpos(
                    realpath($archivoAnterior),
                    realpath($carpeta) . DIRECTORY_SEPARATOR
                ) === 0
            ) {
                unlink($archivoAnterior);
            }
        }
    }

    // Volver al detalle de la pregunta
    $this->redirect(
        "index.php?controller=PreguntaController&accion=index&id=" . (int) $id
    );
}



    public function borrar() {

        // Comprobar que hay un usuario conectado.
        if (!isset($_SESSION['id_usuario'])) {
            $this->redirect("index.php?controller=UsuarioController&accion=login");
            return;
        }

        // El borrado debe realizarse mediante POST.
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect("index.php");
            return;
        }

        // Obtener el ID de la pregunta.
        $id = $_POST['id_pregunta'] ?? null;

        if (!is_numeric($id) || $id < 1) {
            $this->redirect("index.php");
            return;
        }

        // Comprobar que la pregunta existe.
        $pregunta = PreguntaModel::getById($id);

        if (!$pregunta) {
            $this->redirect("index.php");
            return;
        }

        // Obtener el usuario conectado.
        $usuario = $_SESSION['id_usuario'];
        $idUsuario = $usuario->id_usuario;
        $tipoUsuario = $usuario->tipo;

        // Comprobar los permisos.
        $esAutor = (int) $idUsuario === (int) $pregunta['id_usuario'];
        $esAdmin = $tipoUsuario === 'admin';

        if (!$esAutor && !$esAdmin) {
            exit("No tienes permiso para borrar esta pregunta.");
        }

        // Eliminar la pregunta.
        PreguntaModel::delete($id);

        // Volver a la página principal.
        $this->redirect("index.php");
    }

}   