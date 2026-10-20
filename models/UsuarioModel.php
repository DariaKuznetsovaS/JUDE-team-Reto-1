<?php

require_once __DIR__ . '/Database.php';

class UsuarioModel {

    public static function getAll() {
        $db = Database::getConnection();

        $stmt = $db-> prepare("SELECT *
                               FROM usuarios");
        $stmt-> execute();

       
        return  $stmt->fetchAll(PDO::FETCH_ASSOC);
        
    }

    
    public static function getByIdUsuario($id) {

        $db = Database::getConnection();

        $stmt = $db-> prepare("SELECT *
                               FROM usuarios
                               WHERE id_usuario = :id_usuario");

        $stmt-> execute(['id_usuario' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
        
    }


}