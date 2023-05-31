<?php

    // ***** INIT *****
    require_once("../src/init.php");

    // ***** FORM *****
    use clases\form\campo\Atipo;
    use clases\form\campo\Fecha;
    use clases\form\campo\Multiple;
    use clases\form\campo\Numero;
    use clases\form\campo\Texto;
    use clases\form\campo\File;
    use clases\form\claseMain\Formulario;

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
    ), ["input-wrapper","input-wrapper--submit"], "enviar", "enviar", "LOGIN", ["btn", "btn--primary", "shadow-lightgray"]);

    //si el user tiene la sesión iniciada
    if($sesionIniciada){
        //redirect al index
        header("Location: index.php");
        die();
    }else{
        //si el formulario se ha validado
        if ($formulario->validarGlobal()) {
            //buscamos si el user existe
            $consulta = DWESBaseDatos::obtenUsuarioPorMail($db, $email->getValor());
            //si el user existe (si la consulta no está vacía)
            if($consulta != ""){
                //si el user está verificado
                if ($consulta['verificado'] == DWESBaseDatos::VERIFICADO_SI) {
                    //si la pass es correcta
                    if (password_verify($pass->getValor(), $consulta["contra"])) {
                        //nos traemos todos los datos del user
                        $_SESSION['id'] = $consulta['id'];
                        $_SESSION['nombre'] = $consulta['nombre'];
                        $_SESSION['contra'] = $consulta['contra'];
                        $_SESSION['img'] = $consulta['img'];
                        $_SESSION['correo'] = $consulta['correo'];
                        $_SESSION['privilegio'] = $consulta['privilegio'];

                        //eliminamos posibles tokens residuales
                        DWESBaseDatos::eliminaTokensUsuario($db, $consulta['id']);

                        //si el usuario ha pedido recuerdame
                        if ($recuerdame->getValor() != null && in_array("Recuérdame", $recuerdame->getValor())) {
                            //generamos token
                            $token = bin2hex(openssl_random_pseudo_bytes(DWESBaseDatos::LONG_TOKEN));

                            //insertamos token en BD
                            DWESBaseDatos::insertarToken($db, $_SESSION['id'], $token);

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
                // --- USUARIO NO VERIFICADO ---
                }else{
                    $erroresForm['incorrecto'] = "El usuario ".$email->getValor()." no está verificado.
                    Por favor <a href='http://localhost:8000/public/verify.php' class='underline'>verifícalo</a>.";
                }
            // --- USER NO EXISTE --- 
            }else{
                $erroresForm['incorrecto'] = "email/pass incorrectas";
            }
        }
    }
    
    // ********* INFO PARA EL TEMPLATE **********
    $tituloHead = "Iniciar sesión - SeriesBuddies";
    $estiloEspecifico = "./css/login.css";
    $scriptEspecifico = "";
    $content;

    // ********* COMIENZO BUFFER **********
    ob_start();
?>

    <h1 class="title title--form">Bienvenido</h1>
    <!-- pintar global lleva implicito los errores personalizados -->
    <!-- necesario poner placerholder con un espacio vacío para un trick css -->
    <?php $formulario->pintarGlobal(); ?>
    <!-- user/pass incorrectas -->
    <?php if (isset($erroresForm['incorrecto'])) echo "<p class='error'>".$erroresForm['incorrecto']."</p>" ?>
    <?php if (isset($_GET['error'])) echo "<p class='error'>".$_GET['error']."</p>" ?>
    <div class="extra-form-info">
        ¿Aun no eres buddy? <a href="register.php" class="link-enphasis link-body">Regístrate</a>
        <br>
        ¿Contraseña olvidada? <a href="recovery.php" class="link-enphasis link-body">Recupérala</a>
    </div>

<?php

    // ********* FIN BUFFER + LLAMADA AL TEMPLATE **********
    $content = ob_get_contents();
    ob_end_clean();
    require("template.php");
    
?>