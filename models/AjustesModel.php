<?php
require_once __DIR__ . '/Database.php';

class AjustesModel {

    public static function editarUsuario($idUsuario, $nombre, $apellidos, $nombreUsuario, $contrasenna, $tipo){
        $db=Database::getConnection();
        $stmt = $db->prepare("UPDATE usuarios SET
            nombre = :nombre,
            apellidos = :apellidos,
            nombre_usuario = :nombreUsuario,
            contrasenna = :contrasenna,
            tipo = :tipo
            WHERE id_usuario = :idUsuario");

        $stmt->execute([
            'nombre'        => $nombre,
            'apellidos'     => $apellidos,
            'nombreUsuario' => $nombreUsuario,
            'contrasenna'   => $contrasenna,
            'tipo'          => $tipo,
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

    /*JS*/

    public static function existeNombreUsuario($nombreUsuario, $idActual) {
        $db = Database::getConnection();
        $stmt = $db->prepare(
            "SELECT COUNT(*) FROM usuarios
             WHERE nombre_usuario = :nombreUsuario
             AND id_usuario <> :idActual"
        );
        $stmt->execute([
            'nombreUsuario' => $nombreUsuario,
            'idActual'      => $idActual
        ]);
 
        return $stmt->fetchColumn() > 0;
    }
 
    // Devuelve el usuario como objeto (para refrescar la sesión)
    public static function obtenerPorId($id) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM usuarios WHERE id_usuario = :id");
        $stmt->execute(['id' => $id]);
 
        return $stmt->fetch(PDO::FETCH_OBJ);
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