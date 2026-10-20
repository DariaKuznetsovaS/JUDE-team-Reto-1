<?php

require_once __DIR__ . '/Database.php';

class RespuestaModel {
/*
    public static function getAll() {
        $db = Database::getConnection();

        $stmt = $db-> prepare("SELECT r.*, a.ruta AS imagen_ruta 
                               FROM respuestas r
                               LEFT JOIN adjuntos a ON r.id_respuesta = a.id_respuesta");
        $stmt-> execute();

       
        return  $stmt->fetchAll(PDO::FETCH_ASSOC);
        
    } */

    public static function getByPreguntaId($id) {
        $db = Database::getConnection();

        $stmt = $db->prepare("SELECT r.*, a.ruta AS imagen_ruta
                              FROM respuestas r
                              LEFT JOIN adjuntos a ON r.id_respuesta = a.id_respuesta
                              WHERE r.id_pregunta = :id_pregunta");

        $stmt->execute(['id_pregunta' => $id]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


}