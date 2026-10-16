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