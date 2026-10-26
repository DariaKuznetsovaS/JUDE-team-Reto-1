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

    
public static function store($idPregunta, $idUsuario, $texto, $ruta = null, $nombre = null) {
    $db = Database::getConnection();

    // Guardar la respuesta
    $stmt = $db->prepare(
        "INSERT INTO respuestas (id_pregunta, id_usuario, texto)
         VALUES (:id_pregunta, :id_usuario, :texto)"
    );

    $stmt->execute([
        'id_pregunta' => $idPregunta,
        'id_usuario' => $idUsuario,
        'texto' => $texto
    ]);

    // Obtener el ID de la respuesta recién creada
    $idRespuesta = $db->lastInsertId();

    // Si hay un archivo, guardar sus datos en adjuntos
    if ($ruta !== null && $nombre !== null) {
        $stmt = $db->prepare(
            "INSERT INTO adjuntos (id_respuesta, ruta, nombre)
             VALUES (:id_respuesta, :ruta, :nombre)"
        );

        $stmt->execute([
            'id_respuesta' => $idRespuesta,
            'ruta' => $ruta,
            'nombre' => $nombre
        ]);
    }

    return $idRespuesta;
}


}