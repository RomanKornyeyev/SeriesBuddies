/************* DROPS***************/
DROP TABLE IF EXISTS respuestas CASCADE;
DROP TABLE IF EXISTS peticiones CASCADE;
DROP TABLE IF EXISTS tokens CASCADE;
DROP TABLE IF EXISTS usuarios CASCADE;
DROP TABLE IF EXISTS chips CASCADE;
DROP TABLE IF EXISTS chips_usuario CASCADE;

/************* USUARIOS, PETICIONES, ETC ***************/
CREATE TABLE usuarios (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    nombre      VARCHAR(255) NOT NULL,
    contra      VARCHAR(255) NOT NULL,
    img         VARCHAR(255) DEFAULT 'upload/perfiles/default.png',
    correo      VARCHAR(255) NOT NULL UNIQUE,
    descripcion TEXT,
    privilegio  ENUM('admin', 'usuario') NOT NULL,
    verificado  ENUM('si', 'no') NOT NULL,
    fecha_alta  DATETIME DEFAULT NOW(),
    ult_tkn_solicitado DATETIME DEFAULT (NOW() - INTERVAL 1 DAY),
    ult_contacto DATETIME DEFAULT (NOW() - INTERVAL 1 DAY)
);

INSERT INTO usuarios (nombre, contra, correo, privilegio, verificado) VALUES ("admin","$2y$10$.5vCCMbwTyRGf88.STcYBe1R9asP2.j1KB1zQI8UpFiKvVaJB6d9W", "admin@admin.com", "admin", "si");
INSERT INTO usuarios (nombre, contra, correo, privilegio, verificado) VALUES ("anabel","$2y$10$.5vCCMbwTyRGf88.STcYBe1R9asP2.j1KB1zQI8UpFiKvVaJB6d9W", "anabel@anabel.com", "usuario", "si");
INSERT INTO usuarios (nombre, contra, correo, privilegio, verificado) VALUES ("francis","$2y$10$.5vCCMbwTyRGf88.STcYBe1R9asP2.j1KB1zQI8UpFiKvVaJB6d9W", "francis@francis.com", "usuario", "si");
INSERT INTO usuarios (nombre, contra, correo, privilegio, verificado) VALUES ("nataly","$2y$10$.5vCCMbwTyRGf88.STcYBe1R9asP2.j1KB1zQI8UpFiKvVaJB6d9W", "nataly@nataly.com", "usuario", "si");
INSERT INTO usuarios (nombre, contra, correo, privilegio, verificado) VALUES ("doryan","$2y$10$.5vCCMbwTyRGf88.STcYBe1R9asP2.j1KB1zQI8UpFiKvVaJB6d9W", "doryan@doryan.com", "usuario", "si");
INSERT INTO usuarios (nombre, contra, correo, privilegio, verificado) VALUES ("javi","$2y$10$.5vCCMbwTyRGf88.STcYBe1R9asP2.j1KB1zQI8UpFiKvVaJB6d9W", "javi@javi.com", "usuario", "si");
INSERT INTO usuarios (nombre, contra, correo, privilegio, verificado) VALUES ("librerias","$2y$10$.5vCCMbwTyRGf88.STcYBe1R9asP2.j1KB1zQI8UpFiKvVaJB6d9W", "librerias@librerias.com", "usuario", "si");
INSERT INTO usuarios (nombre, contra, correo, privilegio, verificado) VALUES ("henry","$2y$10$.5vCCMbwTyRGf88.STcYBe1R9asP2.j1KB1zQI8UpFiKvVaJB6d9W", "henry@henry.com", "usuario", "si");
INSERT INTO usuarios (nombre, contra, correo, privilegio, verificado) VALUES ("roman","$2y$10$.5vCCMbwTyRGf88.STcYBe1R9asP2.j1KB1zQI8UpFiKvVaJB6d9W", "roman@roman.com", "usuario", "si");
INSERT INTO usuarios (nombre, contra, correo, privilegio, verificado) VALUES ("prueba1","$2y$10$.5vCCMbwTyRGf88.STcYBe1R9asP2.j1KB1zQI8UpFiKvVaJB6d9W", "prueba1@prueba1.com", "usuario", "si");
INSERT INTO usuarios (nombre, contra, correo, privilegio, verificado) VALUES ("prueba2","$2y$10$.5vCCMbwTyRGf88.STcYBe1R9asP2.j1KB1zQI8UpFiKvVaJB6d9W", "prueba2@prueba2.com", "usuario", "si");
INSERT INTO usuarios (nombre, contra, correo, privilegio, verificado) VALUES ("prueba3","$2y$10$.5vCCMbwTyRGf88.STcYBe1R9asP2.j1KB1zQI8UpFiKvVaJB6d9W", "prueba3@prueba3.com", "usuario", "si");

CREATE TABLE peticiones (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    id_emisor       INT NOT NULL,
    id_receptor     INT NOT NULL,
    estado          ENUM('aceptada', 'pendiente', 'rechazada') NOT NULL,
    CONSTRAINT fk_emisor FOREIGN KEY (id_emisor) REFERENCES usuarios(id) ON DELETE CASCADE,
    CONSTRAINT fk_receptor FOREIGN KEY (id_receptor) REFERENCES usuarios(id) ON DELETE CASCADE
);

INSERT INTO peticiones (id_emisor, id_receptor, estado) VALUES (1, 4, 'pendiente');
INSERT INTO peticiones (id_emisor, id_receptor, estado) VALUES (2, 4, 'pendiente');
INSERT INTO peticiones (id_emisor, id_receptor, estado) VALUES (3, 4, 'pendiente');
INSERT INTO peticiones (id_emisor, id_receptor, estado) VALUES (5, 4, 'pendiente');
INSERT INTO peticiones (id_emisor, id_receptor, estado) VALUES (6, 4, 'pendiente');

CREATE TABLE tokens (
    id int auto_increment PRIMARY KEY,
    id_usuario int,
    valor VARCHAR(255),
    expiracion DATETIME NOT NULL DEFAULT (NOW() + INTERVAL 7 DAY),
    CONSTRAINT fk_id_usuario FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE
);


/************* BARRIDO DE TOKENS EXPIRADOS ***************/
/* evento que elimina los tokens expirados (barrido 1vez/día) */
CREATE EVENT eliminar_tokens_expirados
ON SCHEDULE EVERY 1 DAY
DO
    DELETE FROM tokens WHERE expiracion < NOW();

/* podemos ver si se nos ha creado */
SHOW EVENTS \G;

/* IMPORTANTE: para que esto funcione es necesario habilitar el planificador de eventos CON PRIVILEGIOS DE ROOT */
SET GLOBAL event_scheduler = ON;

/* podemos ver si se nos ha habilitado correctamente y si se ha ejecutado */
SHOW GLOBAL VARIABLES WHERE Variable_name LIKE 'e%';
SHOW GLOBAL STATUS WHERE Variable_name LIKE 'E%';
/************* FIN BARRIDO ***************/


/************* SERIES, RESPUESTAS, ETC ***************/
CREATE TABLE respuestas(
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_serie INT NOT NULL,
    id_usuario INT NOT NULL,
    contenido VARCHAR(500) NOT NULL,
    fecha DATETIME NOT NULL DEFAULT NOW(),
    CONSTRAINT fk_res_usuario FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE
);


/************* CHIPS, CHIPS_USUARIO ***************/
CREATE TABLE chips (
    id      INT AUTO_INCREMENT PRIMARY KEY,
    img     VARCHAR(255) NOT NULL DEFAULT 'upload/perfiles/default.png',
    nombre  VARCHAR(50) NOT NULL DEFAULT 'chip'
);

CREATE TABLE chips_usuario (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    id_chip     INT NOT NULL,
    id_usuario  INT NOT NULL,
    CONSTRAINT fk_id_user FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE,
    CONSTRAINT fk_id_chip FOREIGN KEY (id_chip) REFERENCES chips(id) ON DELETE CASCADE
);


INSERT INTO chips (img, nombre) VALUES ('upload/chips/chip_oro.png', 'CHIP - GOLD');     --20 SERIES VISTAS
INSERT INTO chips (img, nombre) VALUES ('upload/chips/chip_plata.png', 'CHIP - SILVER'); --10 SERIES VISTAS
INSERT INTO chips (img, nombre) VALUES ('upload/chips/chip_bronce.png', 'CHIP - BRONZE'); --5 SERIES VISTAS
INSERT INTO chips (img, nombre) VALUES ('upload/chips/chip_inicio.png', 'CHIP - INICIO'); --5 SERIES VISTAS


--Trigger que se ejecuta cuando se hacen inserts en respuestas
/************* ===== ATENCIÓN: QUITAR COMENTARIOS INTERNOS ANTES DE METER EL TRIGGER ===== ***************/
system clear;
drop trigger insertar_medalla_inicio;

DELIMITER //
CREATE TRIGGER insertar_medalla_inicio AFTER INSERT
ON usuarios
FOR EACH ROW
BEGIN
    INSERT INTO chips_usuario (id_chip, id_usuario) VALUES (4, NEW.id);
END;
//
DELIMITER ;

system clear;
drop trigger sumar_medallas;
DELIMITER //
CREATE TRIGGER sumar_medallas AFTER INSERT
ON respuestas
FOR EACH ROW
BEGIN
    -- Variables que se necesitan para controlar errores y resultados
    DECLARE total_series INT;
    DECLARE count_chips_series INT;
    DECLARE count_chip_plata INT;
    DECLARE count_chip_oro INT;

    -- Total de series en las que ha comentado
    SET total_series = (WITH cte AS (SELECT id_serie, id_usuario FROM respuestas GROUP BY id_usuario, id_serie) SELECT count(id_serie) as total_series FROM cte WHERE id_usuario = NEW.id_usuario GROUP BY id_usuario);

    -- Total de chips sin contar el de bienvenida
    SET count_chips_series = (SELECT COUNT(DISTINCT(id_chip)) FROM chips_usuario where id_usuario = NEW.id_usuario and id_chip != 4);
    -- Total de chips de plata que tiene
    SET count_chip_plata = (SELECT count(id_chip) FROM chips_usuario WHERE id_usuario = NEW.id_usuario AND id_chip = 2);
    -- Total de chips de oro que tiene
    SET count_chip_oro = (SELECT count(id_chip) FROM chips_usuario WHERE id_usuario = NEW.id_usuario AND id_chip = 1);

    -- Si no tiene ningun chip ademas del de bienvenida
    IF count_chips_series = 0 THEN
        -- Y el total de series comentadas esta entre 5 y 10, se le inserta el chip de bronce
        IF (total_series >= 5 AND total_series<10) THEN
            INSERT INTO chips_usuario (id_chip, id_usuario) VALUES (3, NEW.id_usuario);
        END IF;
    -- Si tiene el chip de bronce y entonces...
    ELSE
        -- El total de series comentadas (10 - 19) y NO tiene un chip de plata, se le inserta ese chip
        IF (total_series >=10 AND total_series<20 AND count_chip_plata = 0) THEN
            INSERT INTO chips_usuario (id_chip, id_usuario) VALUES (2, NEW.id_usuario);
        END IF;

        -- El total de series comentadas >= 20 y NO tiene un chip de oro, se le inserta ese chip
        IF (total_series >=20 AND count_chip_oro = 0) THEN
            INSERT INTO chips_usuario (id_chip, id_usuario) VALUES (1, NEW.id_usuario);
        END IF;
    END IF;
END;
//
DELIMITER ;

system clear;
drop trigger restar_medallas;
DELIMITER //
CREATE TRIGGER restar_medallas AFTER DELETE
ON respuestas
FOR EACH ROW
BEGIN
    -- Variables que se necesitan para controlar errores y resultados
    DECLARE total_series INT;
    DECLARE count_chip_bronce INT;
    DECLARE count_chip_plata INT;
    DECLARE count_chip_oro INT;

    -- Total de series en las que ha comentado
    SET total_series = (WITH cte AS (SELECT id_serie, id_usuario FROM respuestas GROUP BY id_usuario, id_serie) SELECT count(id_serie) as total_series FROM cte WHERE id_usuario = OLD.id_usuario GROUP BY id_usuario);

    -- Total de chips de bronce que tiene
    SET count_chip_bronce = (SELECT COUNT(id_chip) FROM chips_usuario WHERE id_usuario = OLD.id_usuario AND id_chip = 3);
    -- Total de chips de plata que tiene
    SET count_chip_plata = (SELECT COUNT(id_chip) FROM chips_usuario WHERE id_usuario = OLD.id_usuario AND id_chip = 2);
    -- Total de chips de oro que tiene
    SET count_chip_oro = (SELECT COUNT(id_chip) FROM chips_usuario WHERE id_usuario = OLD.id_usuario AND id_chip = 1);

    
    -- Si el total de series comentadas es menos de 5 y tiene el chip de bronce, se elimina el chip
    IF (total_series < 5 AND count_chip_bronce = 1) THEN
        DELETE FROM chips_usuario WHERE id_chip=3 and id_usuario=OLD.id_usuario;
    END IF;

    -- El total de series comentadas (10 - 19) y tiene el chip de plata, se elimina el chip
    IF (total_series < 10 AND count_chip_plata = 1) THEN
        DELETE FROM chips_usuario WHERE id_chip=2 and id_usuario=OLD.id_usuario;
    END IF;

    -- El total de series comentadas >= 20 y tiene el chip de oro, se elimina el chip
    IF (total_series < 20 AND count_chip_oro = 1) THEN
        DELETE FROM chips_usuario WHERE id_chip=1 and id_usuario=OLD.id_usuario;
    END IF;
END;
//
DELIMITER ;





















/* ********** LEGACY (WITHOUT API) ********** */
-- DROP TABLE IF EXISTS series CASCADE;
-- DROP TABLE IF EXISTS generos CASCADE;
-- DROP TABLE IF EXISTS generos_series CASCADE;

-- CREATE TABLE generos(
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     nombre VARCHAR(255) UNIQUE NOT NULL
-- );

-- CREATE TABLE series(
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     titulo VARCHAR(255) NOT NULL UNIQUE,
--     contenido VARCHAR(500) NULL
-- );

-- CREATE TABLE generos_series (
--     id_genero  INT NOT NULL,
--     id_serie INT NOT NULL,
--     CONSTRAINT fk_genero FOREIGN KEY (id_genero) REFERENCES generos(id) ON DELETE CASCADE,
--     CONSTRAINT fk_serie FOREIGN KEY (id_serie) REFERENCES series(id) ON DELETE CASCADE
-- );

-- CREATE TABLE respuestas(
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     id_post INT NOT NULL,
--     id_usuario INT NOT NULL,
--     contenido VARCHAR(500) NOT NULL,
--     fecha DATETIME NOT NULL DEFAULT NOW(),
--     CONSTRAINT fk_post FOREIGN KEY (id_post) REFERENCES series(id) ON DELETE CASCADE,
--     CONSTRAINT fk_res_usuario FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE
-- );

-- INSERT INTO generos (nombre) VALUES ('Terror');
-- INSERT INTO generos (nombre) VALUES ('Zombie');
-- INSERT INTO generos (nombre) VALUES ('Gore');
-- INSERT INTO generos (nombre) VALUES ('Comedia');
-- INSERT INTO generos (nombre) VALUES ('Telenovela');
-- INSERT INTO generos (nombre) VALUES ('Thriller');
-- INSERT INTO generos (nombre) VALUES ('Psicológico');
-- INSERT INTO generos (nombre) VALUES ('Policiaco');
-- INSERT INTO generos (nombre) VALUES ('Legal');
-- INSERT INTO generos (nombre) VALUES ('Político');
-- INSERT INTO generos (nombre) VALUES ('Romance');
-- INSERT INTO generos (nombre) VALUES ('Romcom');
-- INSERT INTO generos (nombre) VALUES ('Drama');
-- INSERT INTO generos (nombre) VALUES ('Médico');
-- INSERT INTO generos (nombre) VALUES ('Aventuras');
-- INSERT INTO generos (nombre) VALUES ('Ciencia Ficción');
-- INSERT INTO generos (nombre) VALUES ('Reallity');
-- INSERT INTO generos (nombre) VALUES ('Cyberpunk');
-- INSERT INTO generos (nombre) VALUES ('Melodrama');
-- INSERT INTO generos (nombre) VALUES ('Musical');
-- INSERT INTO generos (nombre) VALUES ('Fantasía');
-- INSERT INTO generos (nombre) VALUES ('Documental');
-- INSERT INTO generos (nombre) VALUES ('Acción');
-- INSERT INTO generos (nombre) VALUES ('Sobrenatural');
-- INSERT INTO generos (nombre) VALUES ('Bélico');
-- INSERT INTO generos (nombre) VALUES ('Histórico');
-- INSERT INTO generos (nombre) VALUES ('Anime');

-- INSERT series (titulo) VALUES ('1899');
-- INSERT series (titulo) VALUES ('28 Moons');
-- INSERT series (titulo) VALUES ('A Love So Beautiful');
-- INSERT series (titulo) VALUES ('Alice in Borderland');
-- INSERT series (titulo) VALUES ('All of Us Are Dead');
-- INSERT series (titulo) VALUES ('AlRawabi School for Girls');
-- INSERT series (titulo) VALUES ('Backstreet Rookie');
-- INSERT series (titulo) VALUES ('Bad Boys Have It Coming');
-- INSERT series (titulo) VALUES ('Betaal');
-- INSERT series (titulo) VALUES ('Black Summer');

-- INSERT series (titulo) VALUES ('Blue Period');
-- INSERT series (titulo) VALUES ('Boys Over Flowers');
-- INSERT series (titulo) VALUES ('Brooklyn Nine-Nine');
-- INSERT series (titulo) VALUES ('Business Proposal');
-- INSERT series (titulo) VALUES ('Clean With Passion For Now');
-- INSERT series (titulo) VALUES ('Crash Course in Romance');
-- INSERT series (titulo) VALUES ('Echoes');
-- INSERT series (titulo) VALUES ('Extracurricular');
-- INSERT series (titulo) VALUES ('Extraordinary Attorney Woo');
-- INSERT series (titulo) VALUES ('Extraordinary You');

-- INSERT INTO generos_series VALUES (16, 1);   --1899
-- INSERT INTO generos_series VALUES (6,  1);   --1899
-- INSERT INTO generos_series VALUES (11, 2);   --28 Moons
-- INSERT INTO generos_series VALUES (13, 2);   --28 Moons
-- INSERT INTO generos_series VALUES (11, 3);   --A Love So Beautiful
-- INSERT INTO generos_series VALUES (22, 3);   --A Love So Beautiful
-- INSERT INTO generos_series VALUES (16, 4);   --Alice in Borderland
-- INSERT INTO generos_series VALUES (6,  4);   --Alice in Borderland
-- INSERT INTO generos_series VALUES (2,  5);   --All of Us Are Dead
-- INSERT INTO generos_series VALUES (3,  5);   --All of Us Are Dead
-- INSERT INTO generos_series VALUES (6,  6);   --AlRawabi School for Girls
-- INSERT INTO generos_series VALUES (19, 6);   --AlRawabi School for Girls
-- INSERT INTO generos_series VALUES (4,  7);   --Backstreet Rookie
-- INSERT INTO generos_series VALUES (12, 7);   --Backstreet Rookie
-- INSERT INTO generos_series VALUES (4,  8);   --Bad Boys Have It Coming
-- INSERT INTO generos_series VALUES (11, 8);   --Bad Boys Have It Coming
-- INSERT INTO generos_series VALUES (2,  9);   --Betaal
-- INSERT INTO generos_series VALUES (8,  9);   --Betaal
-- INSERT INTO generos_series VALUES (2,  10);  --Black Summer
-- INSERT INTO generos_series VALUES (6,  10);  --Black Summer

-- INSERT INTO generos_series VALUES (13, 11);  --Blue Period
-- INSERT INTO generos_series VALUES (27, 11);  --Blue Period
-- INSERT INTO generos_series VALUES (11, 12);  --Boys Over Flowers
-- INSERT INTO generos_series VALUES (22, 12);  --Boys Over Flowers
-- INSERT INTO generos_series VALUES (8,  13);  --Brooklyn Nine-Nine
-- INSERT INTO generos_series VALUES (4,  13);  --Brooklyn Nine-Nine
-- INSERT INTO generos_series VALUES (4,  14);  --Business Proposal
-- INSERT INTO generos_series VALUES (12, 14);  --Business Proposal
-- INSERT INTO generos_series VALUES (4,  15);  --Clean With Passion For Now
-- INSERT INTO generos_series VALUES (12, 15);  --Clean With Passion For Now
-- INSERT INTO generos_series VALUES (11, 16);  --Crash Course in Romance
-- INSERT INTO generos_series VALUES (19, 16);  --Crash Course in Romance
-- INSERT INTO generos_series VALUES (6,  17);  --Echoes
-- INSERT INTO generos_series VALUES (24, 17);  --Echoes
-- INSERT INTO generos_series VALUES (6,  18);  --Extracurricular
-- INSERT INTO generos_series VALUES (8,  18);  --Extracurricular
-- INSERT INTO generos_series VALUES (9,  19);  --Extraordinary Attorney Woo
-- INSERT INTO generos_series VALUES (11, 19);  --Extraordinary Attorney Woo
-- INSERT INTO generos_series VALUES (12, 20);  --Extraordinary You
-- INSERT INTO generos_series VALUES (21, 20);  --Extraordinary You


/* -------- METER LAS TABLAS UNA POR UNA -------- */
/* --- USUARIOS --- */
/* USUARIOS */
/*todas las contraseñas son 123456*/
--INSERT INTO usuarios (nombre, contra, correo, privilegio, verificado) VALUES ("admin","$2y$10$.5vCCMbwTyRGf88.STcYBe1R9asP2.j1KB1zQI8UpFiKvVaJB6d9W", "admin@admin.com", "admin", "si");
--INSERT INTO usuarios (nombre, passwd, correo, id_grupo) VALUES ("admin",    "$2y$10$w81xtzxbppAc00SvOZnVjeCiApXlfUh2niuqGj/GkU8usFBdVXGfC", "admin@admin.com", 1);
--INSERT INTO usuarios (nombre, passwd, correo, id_grupo) VALUES ("roman",    "$2y$10$o.Dllzl0HX/FtowHE.q.TOyUMN808I4SrPUkKyWoh9PlLlX1sMTrm", "roman@roman.com", 2);
--INSERT INTO usuarios (nombre, passwd, correo, id_grupo) VALUES ("anabel",   "$2y$10$Bn82Utar3Et2/xOV3r54GuuCS2pZd6y04AonnX0Xxw6wvF8sIAVyi", "anabel@anabel.com", 2);
--INSERT INTO usuarios (nombre, passwd, correo, id_grupo) VALUES ("francis",  "$2y$10$WBdtM/jCZZraIKgzggvJHuG1acWirCKEYTGeIjOg6NsgZfPEeV0mO", "francis@francis.com", 2);
--INSERT INTO usuarios (nombre, passwd, correo, id_grupo) VALUES ("henry",    "$2y$10$WBdtM/jCZZraIKgzggvJHuG1acWirCKEYTGeIjOg6NsgZfPEeV0mO", "henry@henry.com", 2);

/*
/* --- TEMAS/POSTS --- */
/* TEMAS */
--INSERT INTO generos (nombre, descripcion) VALUES ("GAMING",       "Videojuegos, consolas, etc");
--INSERT INTO generos (nombre, descripcion) VALUES ("PROGRAMACION", "JAVA, PHP, ETC.");
--INSERT INTO generos (nombre, descripcion) VALUES ("HARDWARE",     "PCs, portátiles, tarjetas gráficas, etc.");

/* POSTS */
--INSERT INTO series (id_tema, id_usuario, titulo, contenido) VALUES (1, 2, "HOGWARTS LEGACY",     "Es el mejor juego del universo de harry potter sacado hasta la fecha");
--INSERT INTO series (id_tema, id_usuario, titulo, contenido) VALUES (1, 2, "HORIZON ZERO DAWN",   "Es un juego muy divertido, porque bla bla bla...");
--INSERT INTO series (id_tema, id_usuario, titulo, contenido) VALUES (2, 3, "Java",                "Lenguaje de programación mixto de alto nivel");
--INSERT INTO series (id_tema, id_usuario, titulo, contenido) VALUES (3, 4, "PRUEBA",              "Esto es una prueba");
--INSERT INTO series (id_tema, id_usuario, titulo, contenido) VALUES (2, 4, "C++",                 "Lenguaje de programación compilado de alto nivel");


/*RESPUESTAS*/
--INSERT INTO respuestas (id_post, id_usuario, contenido) VALUES (1, 4, "Buen juego, sí!");
--INSERT INTO respuestas (id_post, id_usuario, contenido) VALUES (2, 4, "La verdad es que sí, jeje");
--INSERT INTO respuestas (id_post, id_usuario, contenido) VALUES (3, 2, "Me encanta!");
--INSERT INTO respuestas (id_post, id_usuario, contenido) VALUES (4, 3, "Prefiero Java...");
--INSERT INTO respuestas (id_post, id_usuario, contenido) VALUES (5, 3, "Esto es una prueba");