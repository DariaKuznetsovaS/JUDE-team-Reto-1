
<?php

require_once __DIR__ . '/Database.php';

class PreguntaModel {

    public static function getAll() {
        $db = Database::getConnection();

        $stmt = $db->prepare(
            "SELECT p.*, a.ruta AS imagen_ruta
             FROM preguntas p
             LEFT JOIN adjuntos a ON p.id_pregunta = a.id_pregunta
             ORDER BY fecha_creacion DESC"
        );

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    
public static function getById($id) {
    $db = Database::getConnection();

    $stmt = $db->prepare(
        "SELECT p.*,
                u.nombre_usuario,
                (
                    SELECT MIN(a.ruta)
                    FROM adjuntos a
                    WHERE a.id_pregunta = p.id_pregunta
                ) AS imagen
         FROM preguntas p
         INNER JOIN usuarios u
             ON p.id_usuario = u.id_usuario
         WHERE p.id_pregunta = :id_pregunta"
    );

    $stmt->execute([
        'id_pregunta' => $id
    ]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}


    public static function create($idUsuario, $titulo, $descripcion, $tipo) {
    $db = Database::getConnection();

    $stmt = $db->prepare(
        "INSERT INTO preguntas (id_usuario, titulo, descripcion, tipo)
         VALUES (:id_usuario, :titulo, :descripcion, :tipo)"
    );

    $stmt->execute([
        'id_usuario' => $idUsuario,
        'titulo' => $titulo,
        'descripcion' => $descripcion,
        'tipo' => $tipo
    ]);

    return $db->lastInsertId();
}

    
public static function getByIdUsuario($id) {
    $db = Database::getConnection();

    $stmt = $db->prepare(
        "SELECT p.*, MIN(a.ruta) AS imagen
         FROM preguntas p
         LEFT JOIN adjuntos a
             ON p.id_pregunta = a.id_pregunta
         WHERE p.id_usuario = :id_usuario
         GROUP BY p.id_pregunta
         ORDER BY p.fecha_creacion DESC"
    );

    $stmt->execute([
        'id_usuario' => $id
    ]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


    //Para La pagina de Mis preguntas y Respuestas

    public static function preguntasPorId($id) :array{
        $db = Database::getConnection();

        $stmt = $db->prepare(
            "SELECT p.*, MIN(a.ruta) AS imagen
            FROM preguntas p
            LEFT JOIN adjuntos a
            ON a.id_pregunta = p.id_pregunta
            WHERE p.id_usuario = :id_usuario
            GROUP BY p.id_pregunta
            ORDER BY p.fecha_creacion DESC"
        );
        $stmt->execute(['id_usuario'=> $id]);

        return $stmt->fetchAll();
    }

    public static function guardarAdjunto($idPregunta, $ruta, $nombre) {
    $db = Database::getConnection();

    $stmt = $db->prepare(
        "INSERT INTO adjuntos (id_pregunta, ruta, nombre)
         VALUES (:id_pregunta, :ruta, :nombre)"
    );

    $stmt->execute([
        'id_pregunta' => $idPregunta,
        'ruta' => $ruta,
        'nombre' => $nombre
    ]);
}

    public static function update($id, $titulo, $descripcion, $tipo) {
        $db = Database::getConnection();

        $stmt = $db->prepare("
            UPDATE preguntas
            SET titulo = :titulo,
                descripcion = :descripcion,
                tipo = :tipo
            WHERE id_pregunta = :id
        ");

        return $stmt->execute([
            'titulo' => $titulo,
            'descripcion' => $descripcion,
            'tipo' => $tipo,
            'id' => $id
        ]);
    }

    public static function delete($id) {
        $db = Database::getConnection();

        $stmt = $db->prepare("
            DELETE FROM preguntas
            WHERE id_pregunta = :id
        ");

        return $stmt->execute([
            'id' => $id
        ]);
    }

    
public static function reemplazarAdjuntoPregunta($idPregunta, $ruta, $nombre) {
    $db = Database::getConnection();

    // Obtener las rutas de las imágenes anteriores de la pregunta
    $stmt = $db->prepare(
        "SELECT ruta
         FROM adjuntos
         WHERE id_pregunta = :id_pregunta
           AND id_respuesta IS NULL"
    );

    $stmt->execute([
        'id_pregunta' => $idPregunta
    ]);

    $rutasAnteriores = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // Eliminar las referencias anteriores
    $stmt = $db->prepare(
        "DELETE FROM adjuntos
         WHERE id_pregunta = :id_pregunta
           AND id_respuesta IS NULL"
    );

    $stmt->execute([
        'id_pregunta' => $idPregunta
    ]);

    // Guardar la nueva imagen
    self::guardarAdjunto($idPregunta, $ruta, $nombre);

    return $rutasAnteriores;
}

}
