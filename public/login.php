<?php

    require_once("../src/init.php");
    use form\campo\Atipo;
    use form\campo\Fecha;
    use form\campo\Multiple;
    use form\campo\Numero;
    use form\campo\Texto;
    use form\campo\File;
    use form\claseMain\Formulario;

    // ========================================= FORM DE LOGIN =========================================
    //                             ACTION            METHOD           clases-css-form   ¿Vaciar al validar?   atr-extra(para forms con img)   CAMPOS
    $formulario = new Formulario("login.php", Formulario::METHOD_POST, ["form"],        Formulario::VACIAR_NO,          "",                   array(
        //                       ====================================== COMÚN =======================================================================  //  ======================== ESPECÍFICO ========================
        //                  ¿Puede estar vacío? valor  name    label         clases-css-label         clases-css-wrapper       clases-css-input            tipoCampo    placeholder    regex
        $email = new Texto      (Atipo::NULL_NO, null,"email", "Email",   ["label","label--text"],    ["input-wrapper"],  ["input","shadow-lightgray"],  Texto::TYPE_TEXT, " ",  Texto::EMAIL_PATTERN),
        $pass = new Texto       (Atipo::NULL_NO, null,"contra","Password",["label","label--text"],    ["input-wrapper"],  ["input","shadow-lightgray"],  Texto::TYPE_PSWD, " ",  Texto::DEFAULT_PATTERN_25),
        //                                                                                                                                               clase-label-cada-check             clase-wrapper(chboxes)                    tipoCampo              array (checkboxes, radios, selects)                                               
        $recuerdame = new Multiple(Atipo::NULL_SI, null,"recuerdame",NULL,["label","label--checkbox"],["input-wrapper"],        ["checkbox"],           ["label", "label--checkbox"],["input-wrapper","input-wrapper--checkbox"], Multiple::TYPE_CHECKBOX, ["Recuérdame"])
    // === SUBMIT ===
    // claseWrappSubmit                           idSubmit  nameSubm  txtSubmit  clseSubmit
    ), ["input-wrapper","input-wrapper--submit"], "enviar", "enviar", "ENVIAR", ["btn", "btn--primary", "shadow-lightgray"]);

    //si el user tiene la sesión iniciada
    if($sesionIiniciada){
        //redirect al index
        header("Location: index.php");
        die();
    }else{
        //si el formulario se ha validado
        if ($formulario->validarGlobal()) {
            //hacemos una consulta para ese user
            $consulta = DWESBaseDatos::obtenUsuarioPorMail($db, $email->getValor());
            //si el user existe (si la consulta no está vacía)
            if($consulta != ""){
                //si la pass es correcta
                if (password_verify($pass->getValor(), $consulta["contra"])) {
                    //nos traemos todos los datos del user
                    $_SESSION['id'] = $consulta['id'];
                    $_SESSION['nombre'] = $consulta['nombre'];
                    $_SESSION['contra'] = $consulta['contra'];
                    $_SESSION['img'] = $consulta['img'];
                    $_SESSION['correo'] = $consulta['correo'];
                    $_SESSION['privilegios'] = $consulta['privilegios'];

                    //si el usuario ha pedido recuerdame
                    if ($recuerdame->getValor() != null && in_array("Recuérdame", $recuerdame->getValor())) {
                        //generamos token
                        $token = bin2hex(openssl_random_pseudo_bytes(DWESBaseDatos::LONG_TOKEN));

                        //insertamos token en BD
                        DWESBaseDatos::insertarToken($db, $token);

                        //creamos la cookie
                        setcookie(
                            "recuerdame",
                            $token,
                            [
                                "expires" => time() + 7 * 24 * 60 * 60,
                                /*"secure" => true,*/
                                "httponly" => true
                            ]
                        );
                    }

                    //redirect a página anterior
                    header("Location: ".$paginaAnterior);
                    die();
                    
                // --- PASS INCORRECTA --- 
                }else{
                    $erroresForm['incorrecto'] = "email/pass incorrectas";
                }
            // --- USER NO EXISTE --- 
            }else{
                $erroresForm['incorrecto'] = "email/pass incorrectas";
            }
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
                <!-- user/pass incorrectas -->
                <?php if(isset($erroresForm['incorrecto'])) echo "<p class='error'>".$erroresForm['incorrecto']."</p>" ?>
                <div class="extra-form-info">
                    ¿Aun no eres buddy? <a href="register.php" class="link-enphasis link-body">Regístrate</a>
                    <br>
                    ¿Contraseña olvidada? <a href="recovery.php" class="link-enphasis link-body">Recupérala</a>
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