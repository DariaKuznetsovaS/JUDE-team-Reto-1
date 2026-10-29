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
        
        
        $db = Database::getConnection();

        $stmt = $db-> prepare("SELECT * FROM usuarios");
        $stmt -> execute();

        return $stmt-> fetchAll(PDO::FETCH_ASSOC);

    }
    
    public static function getById($id) {

        $db = Database::getConnection();

        $stmt = $db->prepare(
            "SELECT id_usuario, nombre_usuario, contrasenna, nombre, apellidos, tipo
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


    public static function updateUsuario($id, $datos) {
    $db = Database::getConnection();

    $stmt = $db->prepare("UPDATE usuarios
                          SET nombre_usuario = :nombre_usuario,
                              contrasenna = :contrasenna,
                              nombre = :nombre,
                              apellidos = :apellidos,
                              tipo = :tipo
                          WHERE id_usuario = :id_usuario");

    return $stmt->execute([
        'nombre_usuario' => $datos['nombre_usuario'],
        'contrasenna'    => $datos['contrasenna'],
        'nombre'         => $datos['nombre'],
        'apellidos'      => $datos['apellidos'],
        'tipo'           => $datos['tipo'],
        'id_usuario'     => $id
    ]);
}

    public static function existeNombreUsuario($nombreUsuario) {
        $db = Database::getConnection();
        $stmt = $db->prepare('SELECT COUNT(*) FROM usuarios WHERE nombre_usuario = :u');
        $stmt->execute([':u' => $nombreUsuario]);
        return $stmt->fetchColumn() > 0;
    }


    public static function insertarUsuario() {

        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO usuarios (nombre_usuario, contrasenna, nombre, apellidos, tipo)
                            VALUES :nombre_usuario, :contrasenna, :nombre, :apellidos, :tipo");

        
    }

    public static function deleteUsuarioById($id) {

        $db = Database::getConnection();
        $stmt = $db->prepare('DELETE FROM usuarios WHERE id_usuario = :id_usuario');
        $stmt->execute([':id_usuario' => $id]);
        return $stmt->rowCount() > 0;

    }
    

    public static function contarAdmins() {

        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT COUNT(*) FROM usuarios WHERE tipo = 'Admin'");
        $stmt->execute();
        
        return (int) $stmt->fetchColumn();

    }
}