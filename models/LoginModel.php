<?php
require_once __DIR__ . '/Database.php';

class LoginModel {

    public static function iniciarSesion($nombreUsuario, $contrasenna){
        echo "model";
        $db=Database::getConnection();
        $stmt=$db -> prepare("SELECT * FROM usuarios 
        WHERE nombre_usuario=:nombreUsuario AND contrasenna=:contrasenna");
        $stmt->execute([
            ':nombreUsuario' => $nombreUsuario,
            ':contrasenna'   => $contrasenna
        ]);
        return $stmt->fetch();
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