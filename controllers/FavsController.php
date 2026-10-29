<?php

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/FavsModel.php';

class FavsController extends BaseController {

    public function index() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['id_usuario'])) {
            $this->redirect("index.php?controller=UsuarioController&accion=login");
        }

        $user = (array) $_SESSION['id_usuario'];
        $id = $user['id_usuario'];

        $favsModel = new FavsModel();
        $respuestasFavoritas = $favsModel->getByIdUsuario($id);

        $this->render('favoritos.view.php', [
            'user' => $user,
            'respuestasFavoritas' => $respuestasFavoritas
        ]);
    }

    public function show() {}
    public function store() {}
    public function destroy() {}
    public function destroyAll() {}
}