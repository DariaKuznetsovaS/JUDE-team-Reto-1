<?php
require_once __DIR__ . '/Database.php';

class RegistroModel {

    public static function iniciarSesion($nombreUsuario, $contrasenna){
        echo "model";
        $db=getConnection();
        $stmt=$db -> prepare("SELECT nombre_usuario, contrasenna FROM usuarios where nombre_usuario=:nombreUsuario, contrasenna=:contrasenna");
        $datos([
            ':nombreUsuario' => $nombreUsuario,
            ':contrasenna'   => $contrasenna
        ]);
        $stmt->execute($datos);
        return $stmt->fecth();
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