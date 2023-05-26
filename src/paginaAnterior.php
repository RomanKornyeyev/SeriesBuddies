<?php

    //para que si venimos de una privada, nos mantenga la página anterior visitada
    $paginaAnterior = (isset($_SESSION['anterior'])) ? $_SESSION['anterior'] : "/public/index.php";

    //mientras no sea login, ni register, etc. ni ningún handler, no actualices $session de anterior
    if (
        !preg_match('/login/i', $_SERVER["REQUEST_URI"])
        && $_SERVER["REQUEST_URI"] != "/public/register.php"
        && $_SERVER["REQUEST_URI"] != "/public/verify.php"
        && $_SERVER["REQUEST_URI"] != "/public/recovery.php"
        && !preg_match('/handler/i', $_SERVER["REQUEST_URI"])
    ){
        $_SESSION["anterior"] = $_SERVER["REQUEST_URI"];
    }

?>