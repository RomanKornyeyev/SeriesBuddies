# SeriesBuddies
Proyecto en grupo del trabajo de fin de grado de Desarrollo de Aplicaciones Web.

<hr>

# Tecnologías utilizadas y todo lo necesario (Quick start)
## S.O: Ubuntu 22.04 (LTS)
Utilizaremos Linux como S.O por las facilidades que tenemos a la hora de descargar, ejecutar y usar herramientas y desplegar el sitio web. [Descargar Ubuntu 22.04 LTS](https://releases.ubuntu.com/jammy/). Se puede hacer también en Windows, pero esta guía solo da soporte a Linux.
### PHP
```
sudo apt install php-cli
sudo apt install php-mbstring
```
### MariaDB (MySQL mejorado)
```
sudo apt install mariadb-server
sudo apt install php-mysql
```
### Composer + PHPMailer
Instrucciones sacadas del [sitio oficial](https://getcomposer.org/download/).
<b>Recomiendo instalarlo por el sitio oficial para la última versión</b>. En caso de no estar disponible, ejecutamos los siguientes comandos (1 por 1):
```
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('sha384', 'composer-setup.php') === '55ce33d7678c5a611085589f1f3ddf8b3c52d662cd01d4ba75c0ee0459970c2200a51f492d557530c71c15d8dba01eae') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"
```
lo movemos a /bin para que sea como un comando de linux (y poder utilizarlo como un programa instalado):
```
sudo mv composer.phar /usr/local/bin/composer
```
Posteriormente, <b>en la carpeta del proyecto</b>:
```
composer require phpmailer/phpmailer 
```
De manera <b>opcional (y recomendada)</b>, podemos meter las carpetas generadas por el mailer en el proyecto al .gitignore:
```
echo "vendor/*" >> .gitignore
```
Por último lo requerimos en el init del proyecto (ejecutamos esto en la carpeta raíz del proyecto):
```
require("../vendor/autoload.php");
```
<hr>

## Creación de BD + tablas (todo lo necesario para poder operar)
### Creación de la base de datos
```sql
CREATE DATABASE IF NOT EXISTS seriesBuddies;
CREATE USER IF NOT EXISTS 'user_seriesBuddies'@'localhost' IDENTIFIED BY '123456';
GRANT ALL PRIVILEGES ON seriesBuddies.* TO 'user_seriesBuddies'@'localhost' WITH GRANT OPTION;
```
### Script para la creación de las tablas
<i>*Este archivo está incluído en el proyecto, scripts>db.sql</i>

```sql
DROP TABLE IF EXISTS respuestas CASCADE;
DROP TABLE IF EXISTS series CASCADE;
DROP TABLE IF EXISTS generos CASCADE;
DROP TABLE IF EXISTS peticiones CASCADE;
DROP TABLE IF EXISTS tokens CASCADE;
DROP TABLE IF EXISTS usuarios CASCADE;
DROP TABLE IF EXISTS grupos CASCADE;


/* --- USUARIOS --- */
CREATE TABLE grupos (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    nombre      VARCHAR(16) NOT NULL
);

CREATE TABLE usuarios (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    nombre      VARCHAR(255) NOT NULL UNIQUE,
    passwd      VARCHAR(255) NOT NULL,
    img         VARCHAR(255) DEFAULT 'upload/perfiles/default.png',
    correo      VARCHAR(255) NOT NULL UNIQUE,
    descripcion TEXT,
    id_grupo    INT NOT NULL,
    CONSTRAINT fk_id_grupo FOREIGN KEY (id_grupo) REFERENCES grupos(id)
);

CREATE TABLE tokens (
    id int auto_increment PRIMARY KEY,
    id_usuario int,
    valor VARCHAR(255),
    expiracion DATETIME NOT NULL DEFAULT (NOW() + INTERVAL 7 DAY),
    CONSTRAINT fk_id_usuario FOREIGN KEY (id_usuario) REFERENCES usuarios(id)
);

CREATE TABLE peticiones(
    id              INT AUTO_INCREMENT PRIMARY KEY,
    id_remitente    INT NOT NULL,
    id_receptor     INT NOT NULL,
    estado          ENUM('ACEPTADA', 'PENDIENTE', 'RECHAZADA') NOT NULL,
    CONSTRAINT fk_id_remitente FOREIGN KEY (id_remitente) REFERENCES usuarios(id),
    CONSTRAINT fk_id_recepetor FOREIGN KEY (id_receptor) REFERENCES usuarios(id)
);


/*  --- SERIES Y RESPUESTAS --- */
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
```

### Script de inserts iniciales
<i>*Este archivo está incluído en el proyecto, scripts>db.sql</i>

```sql
/* -------- METER LAS TABLAS UNA POR UNA -------- */
/* --- USUARIOS/GRUPOS --- */
/* GRUPOS */
INSERT INTO grupos (nombre) VALUES ("admin");
INSERT INTO grupos (nombre) VALUES ("usuario");

/* USUARIOS */
/*todas las contraseñas son 123456*/
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
```

## Un último paso...
El último paso antes de empezar es renombrar config.example.php a config.php (o bien copiarlo y ponerle de nombre config.php) y meter las credenciales.