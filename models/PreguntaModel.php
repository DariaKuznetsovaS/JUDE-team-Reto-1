<?php

require_once __DIR__ . '/Database.php';

class PreguntaModel {

    public static function getAll() {
        $db = Database::getConnection();

        $stmt = $db-> prepare("SELECT p.*, a.ruta AS imagen_ruta 
                               FROM preguntas p 
                               LEFT JOIN adjuntos a ON p.id_pregunta = a.id_pregunta");
        $stmt-> execute();

       
        return  $stmt->fetchAll(PDO::FETCH_ASSOC);
        
    }

    public static function getById($id) {

        $db = Database::getConnection();

        $stmt = $db-> prepare("SELECT p.*, a.ruta AS imagen_ruta 
                               FROM preguntas p 
                               LEFT JOIN adjuntos a ON p.id_pregunta = a.id_pregunta
                               WHERE p.id_pregunta = :id_pregunta");

        $stmt-> execute('id_pregunta' => $id);

        return $stmt->fetch(PDO::FETCH_ASSOC);
        
    }


}