
<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/PreguntaModel.php';
require_once __DIR__ . '/../models/RespuestaModel.php';

class RespuestaController extends BaseController {

    public function index() {

    }

    public function show() {

    }

    public function store() {

        // Comprobar que el usuario ha iniciado sesión
        if (!isset($_SESSION['id_usuario'])) {
            $this->redirect(
                "index.php?controller=UsuarioController&accion=login"
            );
            return;
        }

        // Comprobar que el formulario se ha enviado por POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect("index.php");
            return;
        }

        // Recoger los datos del formulario
        $idPregunta = $_POST['id_pregunta'] ?? '';
        $texto = trim($_POST['texto'] ?? '');
        $idUsuario = $_SESSION['id_usuario']->id_usuario;

        // Comprobar que los datos obligatorios existen
        if (!is_numeric($idPregunta) || $texto === '') {
            exit("Debes escribir una respuesta válida.");
        }

        // Comprobar que la pregunta existe y está abierta
        $pregunta = PreguntaModel::getById($idPregunta);

        if (!$pregunta) {
            exit("La pregunta no existe.");
        }

        if ($pregunta['estado'] === 'cerrado') {
            exit("No puedes responder a una pregunta cerrada.");
        }

        // Variables del archivo: inicialmente no hay adjunto
        $ruta = null;
        $nombre = null;
        $archivoFisico = null;

        // Comprobar si se ha seleccionado un archivo
        if (
            isset($_FILES['adjunto']) &&
            $_FILES['adjunto']['error'] !== UPLOAD_ERR_NO_FILE
        ) {
            if ($_FILES['adjunto']['error'] !== UPLOAD_ERR_OK) {
                exit("Se ha producido un error al subir el archivo.");
            }

            // Limitar el tamaño a 5 MB
            if ($_FILES['adjunto']['size'] > 5 * 1024 * 1024) {
                exit("El archivo no puede superar los 5 MB.");
            }

            // Comprobar la extensión del archivo
            $nombreOriginal = basename($_FILES['adjunto']['name']);
            $extension = strtolower(
                pathinfo($nombreOriginal, PATHINFO_EXTENSION)
            );

            $extensionesPermitidas = [
                'pdf', 'png', 'jpg', 'jpeg',
                'txt', 'doc', 'docx', 'xlsx'
            ];

            if (!in_array($extension, $extensionesPermitidas, true)) {
                exit("El tipo de archivo no está permitido.");
            }

            // Crear la carpeta si todavía no existe
            $carpeta = __DIR__ . '/../assets/adjuntos/respuestas/';

            if (!is_dir($carpeta)) {
                mkdir($carpeta, 0755, true);
            }

            // Crear un nombre único para evitar sobrescribir archivos
            $nombreArchivo = uniqid('respuesta_', true) . '.' . $extension;

            $archivoFisico = $carpeta . $nombreArchivo;
            $ruta = 'assets/adjuntos/respuestas/' . $nombreArchivo;
            $nombre = $nombreOriginal;

            // Mover el archivo a la carpeta del proyecto
            if (!move_uploaded_file(
                $_FILES['adjunto']['tmp_name'],
                $archivoFisico
            )) {
                exit("No se ha podido guardar el archivo.");
            }
        }

        // Guardar la respuesta y los datos del adjunto
        try {
            RespuestaModel::store(
                $idPregunta,
                $idUsuario,
                $texto,
                $ruta,
                $nombre
            );
        } catch (PDOException $e) {

            // Si falla el guardado en la base de datos,
            // intentar eliminar el archivo que se acaba de subir
            if ($archivoFisico !== null && file_exists($archivoFisico)) {
                unlink($archivoFisico);
            }

            exit("No se ha podido publicar la respuesta.");
        }

        // Volver al detalle de la pregunta
        $this->redirect(
            "index.php?controller=PreguntaController&accion=index&id="
            . urlencode($idPregunta)
        );
    }


    public function toggleFavorito() {

        header('Content-Type: application/json; charset=utf-8');

        // Comprobar que hay un usuario conectado.
        $sesion = $_SESSION['id_usuario'] ?? null;

        // Si la sesión contiene un objeto usuario, obtener su ID.
        if (is_object($sesion)) {
            $sesion = $sesion->id_usuario ?? null;
        }

        $idUsuario = filter_var($sesion, FILTER_VALIDATE_INT);

        if (!$idUsuario || $idUsuario < 1) {
            http_response_code(401);

            echo json_encode([
                'error' => 'Debes iniciar sesión para guardar favoritos.'
            ]);

            exit;
        }

        // Obtener el ID de la respuesta.
        $idRespuesta = filter_input(
            INPUT_POST,
            'id_respuesta',
            FILTER_VALIDATE_INT
        );

        if (!$idRespuesta || $idRespuesta < 1) {
            http_response_code(400);

            echo json_encode([
                'error' => 'La respuesta no es válida.'
            ]);

            exit;
        }

        try {

            $resultado = RespuestaModel::toggleFavorito(
                $idRespuesta,
                $idUsuario
            );

            echo json_encode($resultado);

        } catch (PDOException $e) {

            http_response_code(500);

            echo json_encode([
                'error' => 'No se ha podido actualizar el favorito.'
            ]);
        }

        exit;
    }

    public function editar() {

    // Comprobar que el usuario ha iniciado sesión.
    if (!isset($_SESSION['id_usuario'])) {
        $this->redirect(
            "index.php?controller=UsuarioController&accion=login"
        );
        return;
    }

    $idRespuesta = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

    if (!$idRespuesta || $idRespuesta < 1) {
        $this->redirect("index.php");
        return;
    }

    // Obtener la respuesta.
    $respuesta = RespuestaModel::getById($idRespuesta);

    if (!$respuesta) {
        exit("La respuesta no existe.");
    }

    // Obtener los datos del usuario conectado.
    $usuario = $_SESSION['id_usuario'];

    $idUsuario = $usuario->id_usuario;
    $tipoUsuario = $usuario->tipo;

    // Comprobar si es el propietario o un administrador.
    if (
        (int) $respuesta['id_usuario'] !== (int) $idUsuario
        && $tipoUsuario !== 'admin'
    ) {
        exit("No tienes permiso para editar esta respuesta.");
    }

    // Cargar el formulario de edición.
    $this->render('editarRespuesta.view.php', [
        'respuesta' => $respuesta
    ]);
}

public function actualizar() {

    // Comprobar que el usuario ha iniciado sesión.
    if (!isset($_SESSION['id_usuario'])) {
        $this->redirect(
            "index.php?controller=UsuarioController&accion=login"
        );
        return;
    }

    // Solo permitimos actualizar mediante POST.
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $this->redirect("index.php");
        return;
    }

    $idRespuesta = filter_var(
        $_POST['id_respuesta'] ?? null,
        FILTER_VALIDATE_INT
    );

    $texto = trim($_POST['texto'] ?? '');

    if (!$idRespuesta || $idRespuesta < 1 || $texto === '') {
        exit("Los datos de la respuesta no son válidos.");
    }

    // Obtener la respuesta actual.
    $respuesta = RespuestaModel::getById($idRespuesta);

    if (!$respuesta) {
        exit("La respuesta no existe.");
    }

    // Comprobar los permisos.
    $usuario = $_SESSION['id_usuario'];

    if (
        (int) $respuesta['id_usuario'] !== (int) $usuario->id_usuario
        && $usuario->tipo !== 'admin'
    ) {
        exit("No tienes permiso para editar esta respuesta.");
    }

    // Inicialmente no se ha seleccionado ningún archivo nuevo.
    $ruta = null;
    $nombre = null;
    $archivoFisicoNuevo = null;

    // Comprobar si se ha seleccionado un archivo.
    if (
        isset($_FILES['adjunto']) &&
        $_FILES['adjunto']['error'] !== UPLOAD_ERR_NO_FILE
    ) {

        if ($_FILES['adjunto']['error'] !== UPLOAD_ERR_OK) {
            exit("Se ha producido un error al subir el archivo.");
        }

        // Limitar el tamaño a 5 MB.
        if ($_FILES['adjunto']['size'] > 5 * 1024 * 1024) {
            exit("El archivo no puede superar los 5 MB.");
        }

        // Comprobar la extensión.
        $nombreOriginal = basename($_FILES['adjunto']['name']);

        $extension = strtolower(
            pathinfo($nombreOriginal, PATHINFO_EXTENSION)
        );

        $extensionesPermitidas = [
            'pdf', 'png', 'jpg', 'jpeg',
            'txt', 'doc', 'docx', 'xlsx'
        ];

        if (!in_array($extension, $extensionesPermitidas, true)) {
            exit("El tipo de archivo no está permitido.");
        }

        // Crear la carpeta si no existe.
        $carpeta = __DIR__ . '/../assets/adjuntos/respuestas/';

        if (!is_dir($carpeta)) {
            mkdir($carpeta, 0755, true);
        }

        // Crear un nombre único para el archivo nuevo.
        $nombreArchivo = uniqid('respuesta_', true) . '.' . $extension;

        $archivoFisicoNuevo = $carpeta . $nombreArchivo;

        $ruta = 'assets/adjuntos/respuestas/' . $nombreArchivo;
        $nombre = $nombreOriginal;

        // Guardar el archivo nuevo.
        if (!move_uploaded_file(
            $_FILES['adjunto']['tmp_name'],
            $archivoFisicoNuevo
        )) {
            exit("No se ha podido guardar el archivo.");
        }
    }

    try {

        // Actualizar el texto de la respuesta.
        $actualizado = RespuestaModel::update($idRespuesta, $texto);

        if (!$actualizado) {
            throw new RuntimeException(
                "No se ha podido actualizar el texto."
            );
        }

        // Si se ha seleccionado un archivo nuevo, actualizar el adjunto.
        if ($archivoFisicoNuevo !== null) {

            $adjuntoGuardado = RespuestaModel::guardarAdjunto(
                $idRespuesta,
                $ruta,
                $nombre
            );

            if (!$adjuntoGuardado) {
                throw new RuntimeException(
                    "No se ha podido actualizar el adjunto."
                );
            }
        }

    } catch (Throwable $e) {

        // Si falla la operación, eliminar el archivo nuevo.
        if (
            $archivoFisicoNuevo !== null &&
            is_file($archivoFisicoNuevo)
        ) {
            unlink($archivoFisicoNuevo);
        }

        exit("No se han podido guardar los cambios de la respuesta.");
    }

    // Si se ha sustituido el archivo, eliminar el anterior.
    if ($archivoFisicoNuevo !== null) {

        $rutaAnterior = $respuesta['adjunto_ruta'] ?? null;

        if ($rutaAnterior !== null) {

            $archivoAnterior = __DIR__ . '/../' . $rutaAnterior;

            if (
                is_file($archivoAnterior) &&
                realpath($archivoAnterior) !== realpath($archivoFisicoNuevo)
            ) {
                unlink($archivoAnterior);
            }
        }
    }

    // Volver al detalle de la pregunta.
    $this->redirect(
        "index.php?controller=PreguntaController&accion=index&id="
        . urlencode($respuesta['id_pregunta'])
    );
}


public function borrar() {

    // Comprobar que el usuario ha iniciado sesión.
    if (!isset($_SESSION['id_usuario'])) {
        $this->redirect(
            "index.php?controller=UsuarioController&accion=login"
        );
        return;
    }

    $idRespuesta = filter_input(INPUT_POST, 'id_respuesta', FILTER_VALIDATE_INT);

    if (!$idRespuesta || $idRespuesta < 1) {
        $this->redirect("index.php");
        return;
    }

    // Obtener la respuesta.
    $respuesta = RespuestaModel::getById($idRespuesta);

    if (!$respuesta) {
        exit("La respuesta no existe.");
    }

    // Comprobar los permisos.
    $usuario = $_SESSION['id_usuario'];

    if (
        (int) $respuesta['id_usuario'] !== (int) $usuario->id_usuario
        && $usuario->tipo !== 'admin'
    ) {
        exit("No tienes permiso para borrar esta respuesta.");
    }

    // Guardar el ID de la pregunta antes de borrar.
    $idPregunta = $respuesta['id_pregunta'];

    RespuestaModel::delete($idRespuesta);

    // Volver al detalle de la pregunta.
    $this->redirect(
        "index.php?controller=PreguntaController&accion=index&id="
        . urlencode($idPregunta)
    );
}

}
