# SeriesBuddies
Proyecto en grupo del trabajo de fin de grado de Desarrollo de Aplicaciones Web. Realizado por:

- [Román](https://github.com/RomanKornyeyev/)
- [Anabel](https://github.com/Anabelix)

<hr>

# Tecnologías utilizadas y todo lo necesario (Quick start)
## S.O: Ubuntu 22.04 (LTS)
Utilizaremos Linux como S.O por las facilidades que tenemos a la hora de descargar, ejecutar y usar herramientas y desplegar el sitio web. [Descargar Ubuntu 22.04 LTS](https://releases.ubuntu.com/jammy/). Se puede hacer también en Windows, pero esta guía solo da soporte a Linux.

### PHP

```
sudo apt install php-cli
sudo apt install php-mbstring
sudo apt install curl
sudo apt install php-curl
```

### MariaDB (MySQL mejorado)

```
sudo apt install mariadb-server
sudo apt install php-mysql
```

### Composer
Necesario instalar composer en caso de no tenerlo, si lo tienes, ignora este paso. Instrucciones sacadas del [sitio oficial](https://getcomposer.org/download/).
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

### Dependencias (PHPMailer)
<b>En la carpeta del proyecto</b> ejecutamos el siguiente comando:

```
composer require phpmailer/phpmailer 
```

o

```
composer install 
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

### CREDENCIALES (EMAIL + API KEY)
#### EMAIL
Es necesario tener una cuenta de email con una **clave de aplicación**, OJO, clave de aplicación, no la clave de acceso de toda la vida. Para ello normalmente es necesario activar la verificación en dos pasos. Aquí mirarse cualquier tutorial.

#### API KEY
Es necesaria una API KEY de desarrollador de [The Movie Database](https://www.themoviedb.org/).

<hr>

## Creación de BD + tablas (todo lo necesario para poder operar)
### Creación BD

```sql
CREATE DATABASE IF NOT EXISTS seriesBuddies;
CREATE USER IF NOT EXISTS 'user_seriesBuddies'@'localhost' IDENTIFIED BY '123456';
GRANT ALL PRIVILEGES ON seriesBuddies.* TO 'user_seriesBuddies'@'localhost' WITH GRANT OPTION;
```

Posteriormente para acceder:

```bash
mariadb -u user_seriesBuddies -p seriesBuddies
```

### Creación tablas + inserts iniciales
Todos los scripts de creación de tablas e inserts inicial están disponibles en [db.sql](./scripts/db.sql).

<hr>

## Unos últimos pasos...
1. Renombrar config.example.php a config.php (o bien copiarlo y ponerle de nombre config.php) y meter las credenciales.

¡Todo listo!