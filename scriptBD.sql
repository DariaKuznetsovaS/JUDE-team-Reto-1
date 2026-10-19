CREATE DATABASE IF NOT EXISTS bd_reto
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE bd_reto;

DROP TABLE IF EXISTS respuestas_favoritas;
DROP TABLE IF EXISTS adjuntos;
DROP TABLE IF EXISTS respuestas;
DROP TABLE IF EXISTS preguntas;
DROP TABLE IF EXISTS usuarios;

CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre_usuario VARCHAR(200) NOT NULL UNIQUE,
    contrasenna VARCHAR(255) NOT NULL,
    nombre VARCHAR(200) NOT NULL,
    apellidos VARCHAR(200) NOT NULL,
    tipo ENUM('admin', 'user') NOT NULL DEFAULT 'user'
);

CREATE TABLE preguntas (
    id_pregunta INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    titulo VARCHAR(200) NOT NULL,
    descripcion TEXT NOT NULL,
    fecha_creacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    estado ENUM('abierto', 'cerrado') NOT NULL DEFAULT 'abierto',
    tipo ENUM(
        'aerodinamica',
        'materiales',
        'motores',
        'software',
        'procesos',
        'diseño',
        'otros'
    ) NOT NULL DEFAULT 'otros',

    CONSTRAINT fk_preguntas_usuarios
        FOREIGN KEY (id_usuario)
        REFERENCES usuarios(id_usuario)
        ON DELETE CASCADE
);

CREATE TABLE respuestas (
    id_respuesta INT AUTO_INCREMENT PRIMARY KEY,
    id_pregunta INT NOT NULL,
    id_usuario INT NOT NULL,
    texto TEXT NOT NULL,
    fecha DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_respuestas_preguntas
        FOREIGN KEY (id_pregunta)
        REFERENCES preguntas(id_pregunta)
        ON DELETE CASCADE,

    CONSTRAINT fk_respuestas_usuarios
        FOREIGN KEY (id_usuario)
        REFERENCES usuarios(id_usuario)
        ON DELETE CASCADE
);


CREATE TABLE adjuntos (
    id_adjunto INT AUTO_INCREMENT PRIMARY KEY,
    id_pregunta INT NULL,
    id_respuesta INT NULL,
    ruta VARCHAR(500) NOT NULL,
    nombre VARCHAR(255) NOT NULL,

    CONSTRAINT fk_adjuntos_preguntas
        FOREIGN KEY (id_pregunta)
        REFERENCES preguntas(id_pregunta)
        ON DELETE CASCADE,

    CONSTRAINT fk_adjuntos_respuestas
        FOREIGN KEY (id_respuesta)
        REFERENCES respuestas(id_respuesta)
        ON DELETE CASCADE
);

CREATE TABLE respuestas_favoritas (
    id_respuesta INT NOT NULL,
    id_usuario INT NOT NULL,

    PRIMARY KEY (id_respuesta, id_usuario),

    CONSTRAINT fk_favoritas_usuarios
        FOREIGN KEY (id_usuario)
        REFERENCES usuarios(id_usuario)
        ON DELETE CASCADE,

    CONSTRAINT fk_favoritas_respuestas
        FOREIGN KEY (id_respuesta)
        REFERENCES respuestas(id_respuesta)
        ON DELETE CASCADE
);

INSERT INTO usuarios (nombre_usuario, contrasenna, nombre, apellidos, tipo)
VALUES ('admin1', '123Abc', 'Administrador', '1', 'admin');

INSERT INTO usuarios (nombre_usuario, contrasenna, nombre, apellidos)
VALUES ('user1', '123Abc', 'Usuario', '1');

INSERT INTO usuarios (nombre_usuario, contrasenna, nombre, apellidos)
VALUES ('user2', '123Abc', 'Usuario', '2');


-- TRIGGERS

-- 1. No eliminar al último administrador
DELIMITER //
CREATE TRIGGER trg_ultimo_admin
BEFORE DELETE ON usuarios
FOR EACH ROW
BEGIN
    IF OLD.tipo = 'admin' AND
       (SELECT COUNT(*) FROM usuarios WHERE tipo = 'admin') = 1 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'No se puede eliminar al último administrador';
    END IF;
END//
DELIMITER ;

-- 2. No cambiar al último administrador 
DELIMITER //
CREATE TRIGGER trg_ultimo_admin_update
BEFORE UPDATE ON usuarios
FOR EACH ROW
BEGIN
    IF OLD.tipo = 'admin' AND NEW.tipo <> 'admin' AND
       (SELECT COUNT(*) FROM usuarios WHERE tipo = 'admin') = 1 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'No se puede degradar al último administrador';
    END IF;
END//
DELIMITER ;

-- 3. No responder a preguntas cerradas
DELIMITER //
CREATE TRIGGER trg_no_responder_cerrada
BEFORE INSERT ON respuestas
FOR EACH ROW
BEGIN
    DECLARE v_estado VARCHAR(10);

    SELECT estado INTO v_estado
    FROM preguntas
    WHERE id_pregunta = NEW.id_pregunta;

    IF v_estado = 'cerrado' THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'La pregunta está cerrada';
    END IF;
END//
DELIMITER ;

-- 4. Adjuntos: destino válido y máximo 3 por destino
DELIMITER //
CREATE TRIGGER trg_limite_adjuntos
BEFORE INSERT ON adjuntos
FOR EACH ROW
BEGIN
    DECLARE total_adjuntos INT DEFAULT 0;

    
    IF (NEW.id_pregunta IS NULL AND NEW.id_respuesta IS NULL)
       OR (NEW.id_pregunta IS NOT NULL AND NEW.id_respuesta IS NOT NULL) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Un adjunto debe pertenecer a una pregunta o a una respuesta, pero no a ambas.';
    END IF;

    IF NEW.id_pregunta IS NOT NULL THEN
        SELECT COUNT(*) INTO total_adjuntos
        FROM adjuntos
        WHERE id_pregunta = NEW.id_pregunta;

        IF total_adjuntos >= 3 THEN
            SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Límite alcanzado: Una pregunta no puede tener más de 3 adjuntos.';
        END IF;

    ELSEIF NEW.id_respuesta IS NOT NULL THEN
        SELECT COUNT(*) INTO total_adjuntos
        FROM adjuntos
        WHERE id_respuesta = NEW.id_respuesta;

        IF total_adjuntos >= 3 THEN
            SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Límite alcanzado: Una respuesta no puede tener más de 3 adjuntos.';
        END IF;
    END IF;
END//
DELIMITER ;

-- 5. Adjuntos: mantener la regla del destino también en UPDATE
DELIMITER //
CREATE TRIGGER trg_adjuntos_destino_update
BEFORE UPDATE ON adjuntos
FOR EACH ROW
BEGIN
    IF (NEW.id_pregunta IS NULL AND NEW.id_respuesta IS NULL)
       OR (NEW.id_pregunta IS NOT NULL AND NEW.id_respuesta IS NOT NULL) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Un adjunto debe pertenecer a una pregunta o a una respuesta, pero no a ambas.';
    END IF;
END//
DELIMITER ;


-- 6. Insertar preguntas de ejemplo(no se necesitan delimeter porque no son triggers)
INSERT INTO preguntas (id_usuario, titulo, descripcion, tipo, estado)
VALUES
(
    2,
    '¿Cómo afecta la humedad a los materiales compuestos?',
    'Estamos estudiando el uso de materiales compuestos en componentes aeronáuticos. ¿Qué efectos puede tener la exposición prolongada a la humedad sobre sus propiedades mecánicas y cómo se puede prevenir su deterioro?',
    'materiales',
    'abierto'
),
(
    3,
    'Problemas de estabilidad durante el vuelo',
    'En una simulación aerodinámica hemos detectado oscilaciones inesperadas durante determinadas maniobras. ¿Qué parámetros deberíamos revisar para identificar la causa del problema?',
    'aerodinamica',
    'abierto'
),
(
    2,
    '¿Qué mantenimiento preventivo necesitan las turbinas?',
    '¿Cuáles son las inspecciones preventivas más importantes para detectar desgaste en los componentes de una turbina aeronáutica antes de que provoquen una avería?',
    'motores',
    'abierto'
),
(
    3,
    'Error al procesar archivos de simulación',
    'Nuestra herramienta de simulación deja de procesar archivos de gran tamaño y muestra errores de memoria. ¿Qué estrategias podemos aplicar para optimizar el procesamiento sin perder precisión en los resultados?',
    'software',
    'abierto'
),
(
    2,
    'Cómo mejorar la trazabilidad de las piezas',
    'Necesitamos mejorar el seguimiento de las piezas durante las distintas fases de fabricación y montaje. ¿Qué métodos o sistemas recomendáis para mantener una trazabilidad completa?',
    'procesos',
    'abierto'
),
(
    3,
    'Criterios para reducir el peso de una estructura',
    'Estamos revisando el diseño de un soporte estructural para reducir su peso sin comprometer su resistencia. ¿Qué criterios deberíamos considerar durante la fase de diseño?',
    'diseño',
    'abierto'
),
(
    1,
    'Buenas prácticas para documentar conocimiento técnico',
    '¿Qué información debería incluir un informe técnico para que otros ingenieros puedan comprender el problema, reproducir las pruebas realizadas y reutilizar la solución en futuros proyectos?',
    'otros',
    'abierto'
),
(
    2,
    'Corrosión en componentes aeronáuticos',
    'Hemos detectado signos de corrosión superficial en un componente metálico. ¿Qué factores ambientales pueden acelerar este proceso y qué medidas preventivas se pueden aplicar?',
    'materiales',
    'abierto'
),
(
    3,
    'Comparación de resultados entre simulación y pruebas reales',
    'Los resultados de una simulación no coinciden completamente con los obtenidos durante las pruebas experimentales. ¿Qué posibles causas deberíamos investigar para explicar las diferencias?',
    'aerodinamica',
    'abierto'
),
(
    2,
    'Automatización de informes de ensayos',
    'Actualmente elaboramos los informes de ensayo manualmente. ¿Qué partes del proceso podrían automatizarse para reducir errores y ahorrar tiempo sin perder trazabilidad?',
    'software',
    'abierto'
),
(
    3,
    'Gestión de incidencias durante el montaje',
    '¿Cómo podemos registrar y clasificar las incidencias que aparecen durante el montaje para detectar problemas recurrentes y compartir las soluciones entre equipos?',
    'procesos',
    'abierto'
),
(
    1,
    'Revisión de un diseño antes de fabricar un prototipo',
    '¿Qué comprobaciones técnicas conviene realizar antes de fabricar un prototipo para detectar posibles errores de diseño y reducir el número de iteraciones?',
    'diseño',
    'cerrado'
);

