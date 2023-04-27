<?php

    require("../src/init.php");
    use form\campo\Atipo;
    use form\campo\Fecha;
    use form\campo\Multiple;
    use form\campo\Numero;
    use form\campo\Texto;
    use form\campo\File;
    use form\claseMain\Formulario;

    // ================================= INICIALIZACIÓN DEL FORM =================================
    //                             ACTION            METHOD           clases-css-form   ¿Vaciar al validar?   atr-extra(para forms con img)   CAMPOS
    $formulario = new Formulario("login.php", Formulario::METHOD_POST, ["form"],        Formulario::VACIAR_NO,          "",                   array(
        //                         ====================================== COMÚN ==============================================================================  //  ======================== ESPECÍFICO ========================
        //                     ¿Puede estar vacío?  valor    name       label       clases-css-label         clases-css-wrapper  clases-css-input                  tipoCampo       placeholder         regex
        $email = new Texto         (Atipo::NULL_NO, null, "email",     "Email",    ["label","label--text"], ["input-wrapper"],  ["input","shadow-lightgray"],  Texto::TYPE_TEXT, " ",  Texto::DEFAULT_PATTERN_25),
        $pass = new Texto          (Atipo::NULL_NO, null, "contraseña","Password", ["label","label--text"], ["input-wrapper"],  ["input","shadow-lightgray"],  Texto::TYPE_PSWD, " ",  Texto::DEFAULT_PATTERN_25),
        //                                                                                                                                                     clase-label-cada-check       clase-wrapper(chboxes)                             tipoCampo         array (checkboxes, radios, selects)                                               
        $generos = new Multiple    (Atipo::NULL_SI, null, "generos",      NULL,  ["label","label--checkbox"],["input-wrapper"],        ["checkbox"],           ["label", "label--checkbox"],["input-wrapper","input-wrapper--checkbox"], Multiple::TYPE_CHECKBOX, ["Recuérdame"])
    // === SUBMIT ===
    // claseWrappSubmit                           idSubmit  nameSubm  txtSubmit  clseSubmit
    ), ["input-wrapper","input-wrapper--submit"], "enviar", "enviar", "ENVIAR", ["btn", "btn--primary", "shadow-lightgray"]);

    //si el formulario se ha validado
    if ($formulario->validarGlobal()) {
        //hace algo
        // echo "correcto";
        
    }

    
    //print_r($_SESSION['usuario']);
    $user = $_SESSION['usuario'];
    print_r($user);
    // echo $user->getNombre();

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
    <title>Login</title>

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
                <h1 class="title title--form">Bienvenido</h1>
                <!-- pintar global lleva implicito los errores personalizados -->
                <!-- necesario poner placerholder con un espacio vacío para un trick css -->
                <?php $formulario->pintarGlobal(); ?>
                <div class="extra-form-info">
                    ¿Aun no eres buddy? <a href="register.php" class="link-enphasis link-body">Regístrate</a>
                    <br>
                    ¿Contraseña olvidada?<a href="recovery.php" class="link-enphasis link-body">Recupérala</a>
                </div>
            </main>

             <!-- bg fixed -->
            <div class="bg-fixed"></div>
        </div>
       

        <!-- footer -->
        <?php include_once('footer.php'); ?>
    </div>
</body>
</html>