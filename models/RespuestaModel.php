<?php

require_once __DIR__ . '/Database.php';

class RespuestaModel {

    public static function getByIdUsuario($id){
        $db = Database::getConnection();

        $stmt = $db->prepare(
            "SELECT r.*, a.ruta AS imagen_ruta
            FROM respuestas r
            LEFT JOIN adjuntos a ON r.id_respuesta = a.id_respuesta
            WHERE r.id_usuario = :id
            ORDER BY r.fecha DESC"
        );

        $stmt->execute(['id' => $id]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getByPreguntaId($id, $idUsuario = 0) {

        $db = Database::getConnection();

        $stmt = $db->prepare("
            SELECT
                r.*,
                u.nombre_usuario,
                a.ruta AS imagen_ruta,

                (
                    SELECT COUNT(*)
                    FROM respuestas_favoritas rf
                    WHERE rf.id_respuesta = r.id_respuesta
                ) AS total_favoritos,

                EXISTS (
                    SELECT 1
                    FROM respuestas_favoritas rf2
                    WHERE rf2.id_respuesta = r.id_respuesta
                    AND rf2.id_usuario = :id_usuario
                ) AS usuario_favorito

            FROM respuestas r

            INNER JOIN usuarios u
                ON r.id_usuario = u.id_usuario

            LEFT JOIN adjuntos a
                ON r.id_respuesta = a.id_respuesta

            WHERE r.id_pregunta = :id_pregunta

            ORDER BY r.fecha ASC
        ");

        $stmt->execute([
            'id_usuario' => $idUsuario,
            'id_pregunta' => $id
        ]);

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

    
    public static function toggleFavorito($idRespuesta, $idUsuario) {

        $db = Database::getConnection();

        // Comprobar si el usuario ya ha marcado la respuesta.
        $stmt = $db->prepare("
            SELECT COUNT(*)
            FROM respuestas_favoritas
            WHERE id_respuesta = :id_respuesta
            AND id_usuario = :id_usuario
        ");

        $stmt->execute([
            'id_respuesta' => $idRespuesta,
            'id_usuario' => $idUsuario
        ]);

        $existe = (int) $stmt->fetchColumn() > 0;

        if ($existe) {

            // Si ya es favorita, quitarla.
            $stmt = $db->prepare("
                DELETE FROM respuestas_favoritas
                WHERE id_respuesta = :id_respuesta
                AND id_usuario = :id_usuario
            ");

            $stmt->execute([
                'id_respuesta' => $idRespuesta,
                'id_usuario' => $idUsuario
            ]);

            $favorito = false;

        } else {

            // Si no es favorita, guardarla.
            $stmt = $db->prepare("
                INSERT INTO respuestas_favoritas
                    (id_respuesta, id_usuario)
                VALUES
                    (:id_respuesta, :id_usuario)
            ");

            $stmt->execute([
                'id_respuesta' => $idRespuesta,
                'id_usuario' => $idUsuario
            ]);

            $favorito = true;
        }

        // Obtener el número actualizado de favoritos.
        $stmt = $db->prepare("
            SELECT COUNT(*)
            FROM respuestas_favoritas
            WHERE id_respuesta = :id_respuesta
        ");

        $stmt->execute([
            'id_respuesta' => $idRespuesta
        ]);

        $total = (int) $stmt->fetchColumn();

        return [
            'favorito' => $favorito,
            'total' => $total
        ];
    }


    public static function getFavoritosByUsuario($idUsuario) {

        $db = Database::getConnection();

        $stmt = $db->prepare("
            SELECT
                r.*,
                u.nombre_usuario,
                a.ruta AS imagen_ruta,
                (
                    SELECT COUNT(*)
                    FROM respuestas_favoritas rf
                    WHERE rf.id_respuesta = r.id_respuesta
                ) AS total_favoritos
            FROM respuestas_favoritas f
            INNER JOIN respuestas r
                ON f.id_respuesta = r.id_respuesta
            INNER JOIN usuarios u
                ON r.id_usuario = u.id_usuario
            LEFT JOIN adjuntos a
                ON r.id_respuesta = a.id_respuesta
            WHERE f.id_usuario = :id_usuario
            ORDER BY r.fecha DESC
        ");

        $stmt->execute([
            'id_usuario' => $idUsuario
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



     //Para La pagina de Mis preguntas y Respuestas

    public static function respuestasPorId($id): array {
        $db = Database::getConnection();

        $stmt = $db->prepare(
            "SELECT r.*, MIN(a.ruta) AS imagen
            FROM respuestas r
            LEFT JOIN adjuntos a
                    ON a.id_respuesta = r.id_respuesta
            WHERE r.id_usuario = :id_usuario
            GROUP BY r.id_respuesta
            ORDER BY r.fecha DESC"
        );
        $stmt->execute(['id_usuario' => $id]);

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public static function getById($id) {

    $db = Database::getConnection();

    $stmt = $db->prepare("
        SELECT r.*, 
               a.ruta AS adjunto_ruta,
               a.nombre AS adjunto_nombre
        FROM respuestas r
        LEFT JOIN adjuntos a
            ON r.id_respuesta = a.id_respuesta
        WHERE r.id_respuesta = :id
        LIMIT 1
    ");

    $stmt->execute(['id' => $id]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}


    public static function update($id, $texto) {

        $db = Database::getConnection();

        $stmt = $db->prepare("
            UPDATE respuestas
            SET texto = :texto
            WHERE id_respuesta = :id
        ");

        return $stmt->execute([
            'texto' => $texto,
            'id' => $id
        ]);
    }


    public static function delete($id) {

        $db = Database::getConnection();

        // Obtener los archivos asociados antes de borrar sus registros.
        $stmt = $db->prepare("
            SELECT ruta
            FROM adjuntos
            WHERE id_respuesta = :id
        ");

        $stmt->execute([
            'id' => $id
        ]);

        $adjuntos = $stmt->fetchAll(PDO::FETCH_COLUMN);

        // Eliminar los favoritos asociados a la respuesta.
        $stmt = $db->prepare("
            DELETE FROM respuestas_favoritas
            WHERE id_respuesta = :id
        ");

        $stmt->execute([
            'id' => $id
        ]);

        // Eliminar los registros de los adjuntos.
        $stmt = $db->prepare("
            DELETE FROM adjuntos
            WHERE id_respuesta = :id
        ");

        $stmt->execute([
            'id' => $id
        ]);

        // Eliminar la respuesta.
        $stmt = $db->prepare("
            DELETE FROM respuestas
            WHERE id_respuesta = :id
        ");

        $stmt->execute([
            'id' => $id
        ]);

        // Eliminar los archivos físicos asociados.
        foreach ($adjuntos as $ruta) {

            $archivo = __DIR__ . '/../' . $ruta;

            if (is_file($archivo)) {
                unlink($archivo);
            }
        }

        return $stmt->rowCount() > 0;
    }

public static function guardarAdjunto($idRespuesta, $ruta, $nombre) {

    $db = Database::getConnection();

    // Comprobar si la respuesta ya tiene un adjunto.
    $stmt = $db->prepare("
        SELECT id_adjunto
        FROM adjuntos
        WHERE id_respuesta = :id
        LIMIT 1
    ");

    $stmt->execute(['id' => $idRespuesta]);

    $adjunto = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($adjunto) {

        // Actualizar el archivo existente.
        $stmt = $db->prepare("
            UPDATE adjuntos
            SET ruta = :ruta, nombre = :nombre
            WHERE id_adjunto = :id_adjunto
        ");

        return $stmt->execute([
            'ruta' => $ruta,
            'nombre' => $nombre,
            'id_adjunto' => $adjunto['id_adjunto']
        ]);

    } else {

        // Si no había archivo, crear su registro.
        $stmt = $db->prepare("
            INSERT INTO adjuntos (id_respuesta, ruta, nombre)
            VALUES (:id_respuesta, :ruta, :nombre)
        ");

        return $stmt->execute([
            'id_respuesta' => $idRespuesta,
            'ruta' => $ruta,
            'nombre' => $nombre
        ]);
    }
}
}