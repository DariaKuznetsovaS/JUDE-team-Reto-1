<?php
require_once __DIR__ . '/Database.php';

class UsuarioModel {

    public static function iniciarSesion($nombreUsuario, $contrasenna){
        $db=Database::getConnection();
        $stmt=$db -> prepare("SELECT * FROM usuarios 
        WHERE nombre_usuario=:nombreUsuario AND contrasenna=:contrasenna");
        $stmt->execute([
            'nombreUsuario' => $nombreUsuario,
            'contrasenna'   => $contrasenna
        ]);
        return $stmt->fetch(PDO::FETCH_OBJ);
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

        $db = Database::getConnection();

        $stmt = $db->prepare(
            "SELECT id_usuario, nombre_usuario, nombre, apellidos, tipo
            FROM usuarios
            WHERE id_usuario = :id_usuario"
        );

        $stmt->execute([
            'id_usuario' => $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public static function create($datos) {
        
    }
    
    public static function deleteById($id) {
        
    }
    
    public static function deleteAll() {
        
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