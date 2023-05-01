<?php

    require_once("../src/init.php");
    use form\campo\Atipo;
    use form\campo\Fecha;
    use form\campo\Multiple;
    use form\campo\Numero;
    use form\campo\Texto;
    use form\campo\File;
    use form\claseMain\Formulario;

    // ================================= INICIALIZACIÓN DEL FORM =================================
    //                        ACTION        METHOD         clases-css-form     ¿Vaciar al validar?   atr-extra(para forms con img)   CAMPOS
    $formulario = new Formulario("", Formulario::METHOD_POST, ["form"],        Formulario::VACIAR_NO,          "",                   array(
        //                         ====================================== COMÚN ==============================================================================  //  ======================== ESPECÍFICO ========================
        //                     ¿Puede estar vacío?  valor    name       label       clases-css-label         clases-css-wrapper  clases-css-input                  tipoCampo       placeholder         regex
        $email = new Texto         (Atipo::NULL_NO, null, "email",     "Email",    ["label","label--text"], ["input-wrapper"],  ["input","shadow-lightgray"],  Texto::TYPE_TEXT, " ",  Texto::DEFAULT_PATTERN_25)
    // === SUBMIT ===
    // claseWrappSubmit                           idSubmit  nameSubm  txtSubmit  clseSubmit
    ), ["input-wrapper","input-wrapper--submit"], "enviar", "enviar", "RECUPERAR", ["btn", "btn--primary", "shadow-lightgray"]);

    //si el formulario se ha validado
    if ($formulario->validarGlobal()) {
        //hace algo
        echo "correcto";
    }

?>
<!DOCTYPE html>
<html lang="es-ES">
<head>
    <!-- META -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Intercambia ideas sobre tus series favoritas">
    <meta name="keywords" content="series, opiniones, ideas">
    <meta name="author" content="Anabel, Román">
    <meta name="copyright" content="Anabel, Román">

    <!-- TITLE -->
    <title>Recovery</title>

    <!-- CSS -->
    <link rel="stylesheet" href="./css/general.css">
    <link rel="stylesheet" href="./css/login.css">

    <!-- JS -->
    <script src="./js/header.js" defer></script>
</head>
<body>
    <!-- global container -->
    <div class="global-container">
        <!-- header -->
        <?php include_once('header.php'); ?>

        <!-- body (central container) -->
        <div class="container limit-width">
            <!-- main -->
            <main class="main">
                <h1 class="title title--form">Recovery</h1>
                <p class="extra-form-info">
                    <b>¿Contraseña olvidada?</b> No pasa nada
                    <br>
                    Déjanos tu email y te enviaremos un enlace para recuperar tu contraseña =)
                </p>
                <!-- pintar global lleva implicito los errores personalizados -->
                <!-- necesario poner placerholder con un espacio vacío para un trick css -->
                <?php $formulario->pintarGlobal(); ?>
                <div class="extra-form-info">
                    ¿La has recordado? <a href="login.php" class="link-enphasis link-body">Inicia sesión</a>
                    <br>
                    ¿Necesitas verificar tu cuenta? <a href="verify.php" class="link-enphasis link-body">Verifícala</a>
                </div>
            </main>

            <!-- posible aside / divs con info extra -->

             <!-- bg fixed -->
            <div class="bg-fixed"></div>
        </div>
       

        <!-- footer -->
        <?php include_once('footer.php'); ?>
    </div>
</body>
</html>