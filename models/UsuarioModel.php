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


    public static function updateUsuario($id,$datos) {

        $db = Database::getConnection();

        $stmt = $db->prepare("UPDATE usuarios 
                              SET nombre_usuario = :nombre_usuario, contrasenna = :contrasenna, nombre = :nombre, apellidos = :apellidos
                              WHERE id = :id");


        $stmt->bindParam(':id',$id );
        $stmt->bindParam(':nombre_usuario',$datos['nombre_usuario']);
        $stmt->bindParam(':contrasena',$datos['contrasena']);
        $stmt->bindParam(':nombre',$datos['nombre']);
        $stmt->bindParam(':apellido',$datos['apellido']);


        return $stmt->execute();

    }


}