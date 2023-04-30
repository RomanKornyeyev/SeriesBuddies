<?php
    //autoload
    spl_autoload_register(function ($class) {
        // Prefijo del namespace de tu proyecto
        $prefix = 'form\\';

        // Directorio base para el prefijo del namespace
        $base_dir = __DIR__ . '/../form/';

        // Verificar si la clase utiliza el prefijo del namespace
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) !== 0) {
            // No, sigue con el siguiente autoloader registrado
            return;
        }

        // Obtenemos el nombre de la clase sin el prefijo del namespace
        $relative_class = substr($class, $len);

        // Reemplaza los separadores de namespace por separadores de directorios en el nombre de la clase,
        // y añade el directorio base y la extensión .php
        $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

        // Si el archivo existe, lo requerimos
        if (file_exists($file)) {
            require_once($file);
        }
    });

    //datos de conexión a BD
    require_once("config.php");

    //librería de PDO para acceder a BD
    require_once("DWESBaseDatos.php");

    //instancia de acceso a BD
    $db = DWESBaseDatos::obtenerInstancia();
    $db->inicializa(
        $CONFIG['db_name'],
        $CONFIG['db_user'],
        $CONFIG['db_pass']
    );

    // ********** CLASE USUARIO DESCARTADA DE MOMENTO, DEMASIADOS QUEBRADEROS DE CABEZA **********
    //en caso de usarla, debe ir por encima de session_start
    //datos del usuario (nombre, grupo, nivel de privilegios, etc.)
    //require_once("DatosUsuario.php");

    //sesión
    session_start();

    //cosas relacionadas con la clase DatosUsuario
    // $sesionIniciada = false;
    // $esAdmin = false;
    // $usuario;
    // if (isset($_SESSION['usuario'])) {
    //     $usuario = $_SESSION['usuario'];
    //     $sesionIniciada = true;
    //     ($usuario->esAdmin())? $esAdmin = true : $esAdmin = false;
    // }

    //página anterior (DEBAJO DEL SESION)
    require_once("paginaAnterior.php");

    //estos tienen que ir debajo del session_start(), porque si no, NO EXISTE $_SESSION
    //(DEBAJO DE SESSION_START Y ENCIMA DE DATOSUSUARIO)
    require_once("recuerdame.php");

    //datos del usuario (sesión y demás)(DEBAJO DEL SESION)
    require_once("datosUsuario.php");

    //vendor + mailer
    require_once("../vendor/autoload.php");
    require_once("Mailer.php");

    //errores globales de los forms (user/pass incorrectas, etc.)
    $erroresForm = [];
?>