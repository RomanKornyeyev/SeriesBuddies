<?php

    require_once("../src/init.php");

    // ***** MAILER *****
    use clases\mailer\Mailer;

    // ***** FORMULARIOS *****
    use clases\form\campo\Atipo;
    use clases\form\campo\Fecha;
    use clases\form\campo\Multiple;
    use clases\form\campo\Numero;
    use clases\form\campo\Texto;
    use clases\form\campo\File;
    use clases\form\claseMain\Formulario;

    // ================================= INICIALIZACIÓN DEL FORM =================================
    //                             ACTION            METHOD           clases-css-form   ¿Vaciar al validar?   atr-extra(para forms con img)   CAMPOS
    $formulario = new Formulario("",         Formulario::METHOD_POST, ["form"],        Formulario::VACIAR_NO,  Formulario::ATR_IMG,           array(
        //                         ====================================== COMÚN ==============================================================================  //  ======================== ESPECÍFICO ========================
        //                     ¿Puede estar vacío?  valor    name       label       clases-css-label         clases-css-wrapper  clases-css-input                  tipoCampo     placeholder     regex
        $nombre = new Texto        (Atipo::NULL_NO, null, "nombre",    "Nombre",  ["label","label--text"],    ["input-wrapper"], ["input","shadow-lightgray"],  Texto::TYPE_TEXT, " ",  Texto::DEFAULT_PATTERN_25),
        $email = new Texto         (Atipo::NULL_NO, null, "email",     "Email",   ["label","label--text"],    ["input-wrapper"], ["input","shadow-lightgray"],  Texto::TYPE_TEXT, " ",  Texto::EMAIL_PATTERN),
        $pass = new Texto          (Atipo::NULL_NO, null, "passwd",    "Password",["label","label--text"],    ["input-wrapper"], ["input","shadow-lightgray"],  Texto::TYPE_PSWD, " ",  Texto::DEFAULT_PSWD),
        //                                                                                                                                                      auxGlobalWrapper        imgWrp            clsImg      imgDflt            accept           max size         ruta guardado
        $img = new File            (Atipo::NULL_SI, null, "img",       "",        ["label", "label--img"],["input-wrapper", "input-wrapper--img"], ["d-none"],  ["input-wrapper-aux-center"], ["profile-img-form"], ["img-fit"],File::IMG_DEFAULT, File::ACCEPT_BOTH, File::SIZE_LOW, File::RUTA_PERFIL)
    // === SUBMIT ===
    // claseWrappSubmit                           idSubmit  nameSubm  txtSubmit  clseSubmit
    ), ["input-wrapper","input-wrapper--submit"], "enviar", "enviar", "REGISTRARME", ["btn", "btn--primary", "shadow-lightgray"]);

    $verificacionPendiente = false;
    
    //si tiene sesión iniciada, al index
    if ($sesionIniciada) {
        header("Location: index.php");
        die();
    }else{
        //si el formulario se ha validado
        if ($formulario->validarGlobal()) {
            //comprobamos si el user ya existe
            $consulta = DWESBaseDatos::obtenUsuarioPorMail($db, $email->getValor());

            //si el user está disponible
            if($consulta == ""){
                //insertamos nuevo user (sin img todavía)
                DWESBaseDatos::insertarUsuario($db, $nombre->getValor(), password_hash($pass->getValor(), PASSWORD_DEFAULT), $email->getValor(), DWESBaseDatos::USUARIO, DWESBaseDatos::VERIFICADO_NO);

                //buscamos el nuevo user (para sacar el ID)
                $nuevoUsuario = DWESBaseDatos::obtenUsuarioPorMail($db, $email->getValor());

                //subimos la imagen y la insertamos en el user
                if (isset($_FILES[$img->getName()]) && $_FILES[$img->getName()]['error'] == 0) {
                    //subimos y guardamos la img en:                              ruta          nombre         ext
                    move_uploaded_file($_FILES[$img->getName()]['tmp_name'], $img->getRuta().$nuevoUsuario['id'].".png");

                    //actualizamos la img en el user
                    DWESBaseDatos::actualizarImgUsuario($db, $nuevoUsuario['id'], $img->getRuta().$nuevoUsuario['id'].".png");
                }

                //generamos token
                $token = bin2hex(openssl_random_pseudo_bytes(DWESBaseDatos::LONG_TOKEN));

                //insertamos token en BD
                DWESBaseDatos::insertarToken($db, $nuevoUsuario['id'], $token);

                //mandamos mail de confirmación
                Mailer::sendEmail(
                    $email->getValor(),
                    "Completa tu registro - SeriesBuddies",
                    "¡Bienvenido a SeriesBuddies ".$nuevoUsuario['nombre']."! Completa tu registro con el siguiente enlace: 
                    <a target='_blank' href='http://localhost:8000/public/verify.php?token=".$token."'>COMPLETAR MI REGISTRO</a>"                
                );

                $verificacionPendiente = true;

            // --- EL USER YA EXISTE (SIN VERIFICAR) --- 
            }else if($consulta['verificado'] == DWESBaseDatos::VERIFICADO_NO){
                $erroresForm['incorrecto'] = "El usuario ".$email->getValor()." ya existe, pero no está verificado.
                Por favor <a href='http://localhost:8000/public/verify.php' class='underline'>verifícalo</a>.";
            // --- EL USER YA EXISTE (VERIFICADO) --- 
            }else{
                $erroresForm['incorrecto'] = "El usuario ".$email->getValor()." ya existe";
            }

        }
    }

    // ********* INFO PARA EL TEMPLATE **********
    $tituloHead = "Registro - SeriesBuddies";
    $estiloEspecifico = "./css/login.css";
    $scriptEspecifico = "";
    $content;

    // ********* COMIENZO BUFFER **********
    ob_start();
?>

    <h1 class="title title--form">Regístrate</h1>
    <?php if($verificacionPendiente){ ?>
        <p class="extra-form-info">
            <i class="fa-sharp fa-solid fa-circle-check checkmark-form"></i>
            <br><br>
            <strong>¡Correo de verificación enviado!</strong> Revisa tu bandeja de entrada.
            <br><br>
            ¿Has verificado ya tu cuenta?
            <br>
            <a href="login.php" class="link-enphasis link-body">Inicia sesión</a>
        </p>
    <?php }else{?>
        <?php $formulario->pintarGlobal(); ?>
        <!-- user/pass incorrectas -->
        <?php if(isset($erroresForm['incorrecto'])) echo "<p class='error'>".$erroresForm['incorrecto']."</p>" ?>
        <div class="extra-form-info">
            ¿Ya eres buddy? <a href="login.php" class="link-enphasis link-body">Inicia sesión</a>
            <br>
            ¿Necesitas verificar tu cuenta? <a href="verify.php" class="link-enphasis link-body">Verifícala</a>
        </div>
    <?php }?>
    

<?php

    // ********* FIN BUFFER + LLAMADA AL TEMPLATE **********
    $content = ob_get_contents();
    ob_end_clean();
    require("template.php");
    
?>