<?php

require_once __DIR__ . '/Database.php';

class FavsModel {

    public function getByIdUsuario($id) {

        $db = Database::getConnection();
        $stmt = $db->prepare("
            SELECT
                p.id_pregunta,
                u.nombre_usuario AS autor,
                r.texto          AS descripcion,
                r.fecha,
                (SELECT a.ruta
                   FROM adjuntos a
                  WHERE a.id_respuesta = r.id_respuesta
                  LIMIT 1) AS imagen_ruta
            FROM respuestas_favoritas rf
            INNER JOIN respuestas r
                ON rf.id_respuesta = r.id_respuesta
            INNER JOIN preguntas p
                ON r.id_pregunta = p.id_pregunta
            INNER JOIN usuarios u
                ON r.id_usuario = u.id_usuario
            WHERE rf.id_usuario = :id_usuario
            ORDER BY r.fecha DESC
        ");

        $stmt->execute([
            'id_usuario' => $id
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}