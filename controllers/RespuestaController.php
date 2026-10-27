
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

}
