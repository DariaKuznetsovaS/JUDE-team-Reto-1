<?php
require_once __DIR__ . '/Database.php';

class AjustesModel {

    public static function editarUsuario($idUsuario, $nombre, $apellidos, $nombreUsuario, $contrasenna){
        $db=Database::getConnection();
        $stmt = $db->prepare("UPDATE usuarios SET
            nombre = :nombre,
            apellidos = :apellidos,
            nombre_usuario = :nombreUsuario,
            contrasenna = :contrasenna
            WHERE id_usuario = :idUsuario");

        $stmt->execute([
            'nombre'        => $nombre,
            'apellidos'     => $apellidos,
            'nombreUsuario' => $nombreUsuario,
            'contrasenna'   => $contrasenna,
            'idUsuario'     => $idUsuario
        ]);
    }

    public static function registrarse($nombreUsuario,$nombre, $apellidos, $contrasenna){
        $db=Database::getConnection();
        $stmt=$db -> prepare("INSERT INTO usuarios (nombre_usuario, contrasenna,nombre, apellidos)
        VALUES (:nombreUsuario, :contrasenna, :nombre, :apellidos);");
        $stmt->execute([
            'nombreUsuario' => $nombreUsuario,
            'contrasenna'   => $contrasenna,
            'nombre' => $nombre,
            'apellidos'=> $apellidos
        ]);
    }
    
    public static function getAll() {
        
    }
    
    public static function getById($id) {
        
    }
    
    public static function create($datos) {
        
    }
    
    public static function deleteById($id) {
        
    }
    
    public static function deleteAll() {
        
    }
}