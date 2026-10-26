<?php

require_once __DIR__ . '/Database.php';

class RespuestaModel {

    function getByIdUsuario($id){

        $db = Database::getConnection();
        $stmt = $db-> prepare("SELECT p.id_pregunta, p.titulo, p.fecha_creacion, r.imagen_ruta, r.descripcion
                                FROM respuestas_favoritas rf
                                INNER JOIN respuestas r
                                    ON rf.id_respuesta = r.id_respuesta
                                INNER JOIN preguntas p
                                    ON r.id_pregunta = p.id_pregunta
                                WHERE rf.id_usuario = :id_usuario");

        $stmt -> execute([
            'id_usuario' => $id
        ]);

        return $stmt-> fetchAll(PDO::FETCH_ASSOC);

    }

}