<?php

    // --- INFO USER ---
    //id user
    $usuarioId;
    //nb user
    $usuarioNombre = "anónimo";
    //pass user
    $usuarioContra;
    //img user
    $usuarioImg;
    //correo user
    $usuarioCorreo;
    //privilegios user
    $usuarioPrivilegios = DWESBaseDatos::USUARIO;

    // --- INFO ADICIONAL ---
    //¿tiene la sesión iniciada?
    $sesionIniciada = false;
    //¿es admin? Por default false, si inicia sesión como admin, true
    $esAdmin = false;

    //si tiene sesión iniciada
    if(isset($_SESSION['nombre'])){
        // --- INFO USER ---
        $usuarioId = $_SESSION['id'];
        $usuarioNombre = $_SESSION['nombre'];
        $usuarioContra = $_SESSION['contra'];
        $usuarioImg = $_SESSION['img'];
        $usuarioCorreo = $_SESSION['correo'];
        $usuarioPrivilegios = $_SESSION['privilegios'];


        // --- INFO ADICIONAL ---
        //sesión iniciada en true (genius)
        $sesionIniciada = true;
        //¿Es admin?
        ($_SESSION['privilegios'] == DWESBaseDatos::ADMIN) ? $esAdmin = true : $esAdmin = false;
    }

?>