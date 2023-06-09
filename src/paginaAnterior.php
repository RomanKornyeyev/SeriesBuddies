<?php

    //para que si venimos de una privada, nos mantenga la página anterior visitada
    $paginaAnterior = (isset($_SESSION['anterior'])) ? $_SESSION['anterior'] : "./index.php";

    //mientras no sea login, ni register, etc. ni ningún handler, no actualices $session de anterior
    if (
        !preg_match('/login/i', $_SERVER["REQUEST_URI"])
        && !preg_match('/register/i', $_SERVER["REQUEST_URI"])
        && !preg_match('/verify/i', $_SERVER["REQUEST_URI"])
        && !preg_match('/recovery/i', $_SERVER["REQUEST_URI"])
        && !preg_match('/handler/i', $_SERVER["REQUEST_URI"])
    ){
        $_SESSION["anterior"] = $_SERVER["REQUEST_URI"];
    }

?>