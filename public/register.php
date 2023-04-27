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
    $formulario = new Formulario("",         Formulario::METHOD_POST, ["form"],        Formulario::VACIAR_NO,          "",                   array(
        //                         ====================================== COMÚN ==============================================================================  //  ======================== ESPECÍFICO ========================
        //                                                                                                                      tipoCampo            min                     max
        $id = new Numero            (Atipo::NULL_SI, null, "id", "id",  ["label","label--text"], ["input-wrapper"],  ["input"],       Numero::TYPE_NUMBER, Numero::MIN_DEFAULT_0, Numero::MAX_10),
        //                     ¿Puede estar vacío?  valor    name       label       clases-css-label         clases-css-wrapper  clases-css-input                  tipoCampo     placeholder     regex
        $nombre = new Texto        (Atipo::NULL_NO, null, "nombre",    "Nombre",   ["label","label--text"], ["input-wrapper"],  ["input","shadow-lightgray"],  Texto::TYPE_TEXT, " ",  Texto::DEFAULT_PATTERN_25),
        $email = new Texto         (Atipo::NULL_NO, null, "email",     "Email",    ["label","label--text"], ["input-wrapper"],  ["input","shadow-lightgray"],  Texto::TYPE_TEXT, " ",  Texto::DEFAULT_PATTERN_25),
        $pass = new Texto          (Atipo::NULL_NO, null, "passwd",    "Password", ["label","label--text"], ["input-wrapper"],  ["input","shadow-lightgray"],  Texto::TYPE_PSWD, " ",  Texto::DEFAULT_PATTERN_25),
        //                                                                                                                                          f_ini             f_fin
        $fecha = new Fecha         (Atipo::NULL_SI, null, "fecha",     "Fecha",    ["label","label--text"], ["input-wrapper"],  ["input"],       Fecha::NOW, Fecha::PLUS_ONE_WEEK),
        //                                                                                                                                                     clase-label-cada-check       clase-wrapper(chboxes)                             tipoCampo         array (checkboxes, radios, selects)                                               
        $generos = new Multiple    (Atipo::NULL_SI, null, "generos",     NULL,     ["label","label--checkbox"],["input-wrapper"],        ["checkbox"],           ["label", "label--checkbox"],["input-wrapper","input-wrapper--checkbox"], Multiple::TYPE_CHECKBOX, ["Recuérdame"])
    // === SUBMIT ===
    // claseWrappSubmit                           idSubmit  nameSubm  txtSubmit  clseSubmit
    ), ["input-wrapper","input-wrapper--submit"], "enviar", "enviar", "REGISTRARME", ["btn", "btn--primary", "shadow-lightgray"]);

    //si el formulario se ha validado
    if ($formulario->validarGlobal()) {
        //hace algo
        // echo "correcto";

        //$id, $nombre = "anónimo", $passwd, $img = "./img/perfiles/default.png", $correo, $descripcion = "", $id_grupo = self::ID_GRUPO_USUARIO
        $user = new DatosUsuario($_POST["id"], $_POST["nombre"], $_POST["passwd"], "./img/perfiles/default.png", $_POST["email"], "descri", DatosUsuario::ID_GRUPO_USUARIO);
        echo $user->getId()."<br>";
        echo $user->getNombre()."<br>";
        echo $user->getPasswd()."<br>";
        echo $user->getImg()."<br>";
        echo $user->getCorreo()."<br>";
        echo $user->getDescripcion()."<br>";
        echo $user->getId_grupo()."<br>";

        if($user->validar()){
            $_SESSION['usuario'] = $user;
            $_SESSION['autenticado'] = true;

            print_r($_SESSION['usuario']);
            echo "<br>";
            echo $_SESSION['usuario']->getNombre();
        }else{
            echo "datos incorrectos";
        }
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
    <title>Regístrate</title>

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
                <h1 class="title title--form">Regístrate</h1>
                <!-- pintar global lleva implicito los errores personalizados -->
                <!-- necesario poner placerholder con un espacio vacío para un trick css -->
                <?php $formulario->pintarGlobal(); ?>
                <div class="extra-form-info">
                    ¿Ya eres buddy? <a href="login.php" class="link-enphasis link-body">Inicia sesión</a>
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