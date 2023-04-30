DROP TABLE IF EXISTS respuestas CASCADE;
DROP TABLE IF EXISTS series CASCADE;
DROP TABLE IF EXISTS generos CASCADE;

DROP TABLE IF EXISTS peticiones CASCADE;
DROP TABLE IF EXISTS tokens CASCADE;
DROP TABLE IF EXISTS usuarios CASCADE;


/* --- USUARIOS --- */
CREATE TABLE usuarios (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    nombre      VARCHAR(255) NOT NULL,
    contra      VARCHAR(255) NOT NULL,
    img         VARCHAR(255) DEFAULT 'upload/perfiles/default.png',
    correo      VARCHAR(255) NOT NULL UNIQUE,
    descripcion TEXT,
    privilegio  ENUM('admin', 'usuario') NOT NULL,
    verificado  ENUM('si', 'no') NOT NULL
);

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

CREATE TABLE peticiones(
    id              INT AUTO_INCREMENT PRIMARY KEY,
    id_remitente    INT NOT NULL,
    id_receptor     INT NOT NULL,
    estado          ENUM('ACEPTADA', 'PENDIENTE', 'RECHAZADA') NOT NULL,
    CONSTRAINT fk_id_remitente FOREIGN KEY (id_remitente) REFERENCES usuarios(id),
    CONSTRAINT fk_id_recepetor FOREIGN KEY (id_receptor) REFERENCES usuarios(id)
);


/*  --- TEMAS Y POSTS --- */
CREATE TABLE generos(
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) UNIQUE NOT NULL,
    descripcion VARCHAR(500)
);

CREATE TABLE series(
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_tema INT NOT NULL,
    id_usuario INT NOT NULL,
    titulo VARCHAR(255) NOT NULL,
    contenido VARCHAR(500) NOT NULL,
    fecha DATETIME NOT NULL DEFAULT NOW(),
    CONSTRAINT fk_tema FOREIGN KEY (id_tema) REFERENCES generos(id) ON DELETE CASCADE,
    CONSTRAINT fk_usuario FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE
);

CREATE TABLE respuestas(
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_post INT NOT NULL,
    id_usuario INT NOT NULL,
    contenido VARCHAR(500) NOT NULL,
    fecha DATETIME NOT NULL DEFAULT NOW(),
    CONSTRAINT fk_post FOREIGN KEY (id_post) REFERENCES series(id) ON DELETE CASCADE,
    CONSTRAINT fk_res_usuario FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE
);




/* -------- METER LAS TABLAS UNA POR UNA -------- */
/* --- USUARIOS --- */
/* USUARIOS */
/*todas las contraseñas son 123456*/
INSERT INTO usuarios (nombre, contra, correo, privilegio, verificado) VALUES ("admin","$2y$10$.5vCCMbwTyRGf88.STcYBe1R9asP2.j1KB1zQI8UpFiKvVaJB6d9W", "admin@admin.com", "admin", "si");
INSERT INTO usuarios (nombre, passwd, correo, id_grupo) VALUES ("admin",    "$2y$10$w81xtzxbppAc00SvOZnVjeCiApXlfUh2niuqGj/GkU8usFBdVXGfC", "admin@admin.com", 1);
INSERT INTO usuarios (nombre, passwd, correo, id_grupo) VALUES ("roman",    "$2y$10$o.Dllzl0HX/FtowHE.q.TOyUMN808I4SrPUkKyWoh9PlLlX1sMTrm", "roman@roman.com", 2);
INSERT INTO usuarios (nombre, passwd, correo, id_grupo) VALUES ("anabel",   "$2y$10$Bn82Utar3Et2/xOV3r54GuuCS2pZd6y04AonnX0Xxw6wvF8sIAVyi", "anabel@anabel.com", 2);
INSERT INTO usuarios (nombre, passwd, correo, id_grupo) VALUES ("francis",  "$2y$10$WBdtM/jCZZraIKgzggvJHuG1acWirCKEYTGeIjOg6NsgZfPEeV0mO", "francis@francis.com", 2);
INSERT INTO usuarios (nombre, passwd, correo, id_grupo) VALUES ("henry",    "$2y$10$WBdtM/jCZZraIKgzggvJHuG1acWirCKEYTGeIjOg6NsgZfPEeV0mO", "henry@henry.com", 2);


/* --- TEMAS/POSTS --- */
/* TEMAS */
INSERT INTO generos (nombre, descripcion) VALUES ("GAMING",       "Videojuegos, consolas, etc");
INSERT INTO generos (nombre, descripcion) VALUES ("PROGRAMACION", "JAVA, PHP, ETC.");
INSERT INTO generos (nombre, descripcion) VALUES ("HARDWARE",     "PCs, portátiles, tarjetas gráficas, etc.");

/* POSTS */
INSERT INTO series (id_tema, id_usuario, titulo, contenido) VALUES (1, 2, "HOGWARTS LEGACY",     "Es el mejor juego del universo de harry potter sacado hasta la fecha");
INSERT INTO series (id_tema, id_usuario, titulo, contenido) VALUES (1, 2, "HORIZON ZERO DAWN",   "Es un juego muy divertido, porque bla bla bla...");
INSERT INTO series (id_tema, id_usuario, titulo, contenido) VALUES (2, 3, "Java",                "Lenguaje de programación mixto de alto nivel");
INSERT INTO series (id_tema, id_usuario, titulo, contenido) VALUES (2, 4, "C++",                 "Lenguaje de programación compilado de alto nivel");
INSERT INTO series (id_tema, id_usuario, titulo, contenido) VALUES (3, 4, "PRUEBA",              "Esto es una prueba");


/*RESPUESTAS*/
INSERT INTO respuestas (id_post, id_usuario, contenido) VALUES (1, 4, "Buen juego, sí!");
INSERT INTO respuestas (id_post, id_usuario, contenido) VALUES (2, 4, "La verdad es que sí, jeje");
INSERT INTO respuestas (id_post, id_usuario, contenido) VALUES (3, 2, "Me encanta!");
INSERT INTO respuestas (id_post, id_usuario, contenido) VALUES (4, 3, "Prefiero Java...");
INSERT INTO respuestas (id_post, id_usuario, contenido) VALUES (5, 3, "Esto es una prueba");