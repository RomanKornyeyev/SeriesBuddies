<?php

    require_once("../src/init.php");
    use form\campo\Atipo;
    use form\campo\Fecha;
    use form\campo\Multiple;
    use form\campo\Numero;
    use form\campo\Texto;
    use form\campo\File;
    use form\claseMain\Formulario;

    // ================================= FORM CON UN LINK DE CONFIRMACIÓN =================================
    //                             ACTION            METHOD           clases-css-form   ¿Vaciar al validar?   atr-extra(para forms con img)   CAMPOS
    $formGet = new Formulario("",         Formulario::METHOD_POST, ["form"],        Formulario::VACIAR_NO,  Formulario::ATR_IMG,           array(
        $recuerdame = new Multiple(Atipo::NULL_SI, null,"recuerdame",NULL,["label","label--checkbox"],["input-wrapper"],        ["checkbox"],           ["label", "label--checkbox"],["input-wrapper","input-wrapper--checkbox", "flex-center-center"], Multiple::TYPE_CHECKBOX, ["Recuérdame"])
        
    ), ["input-wrapper","input-wrapper--submit"], "enviar", "enviar", "COMPLETAR MI REGISTRO", ["btn", "btn--primary", "shadow-lightgray"]);

    // ================================= FORM SIN LINK (INTRODUCIR EMAIL PARA MANDAR DE NUEVO LA VERIFICACIÓN) =================================
    $formNoGet = new Formulario("",         Formulario::METHOD_POST, ["form"],        Formulario::VACIAR_NO,  Formulario::ATR_IMG,           array(
        $email = new Texto         (Atipo::NULL_NO, null, "email",     "Email",   ["label","label--text"],    ["input-wrapper"], ["input","shadow-lightgray"],  Texto::TYPE_TEXT, " ",  Texto::EMAIL_PATTERN)
    ), ["input-wrapper","input-wrapper--submit"], "enviar", "enviar", "ENVIAR", ["btn", "btn--primary", "shadow-lightgray"]);

    $estado = "sin-link";
    
    // ********** USER LLEGA SIN LINK DE VERIFICACIÓN **********
    if (isset($_GET['token'])) {
        //comprobamos que el token exista
        $consulta = DWESBaseDatos::obtenToken($db, $_GET['token']);
        if($consulta != ""){
            $estado = "con-link-valido";
            //comprobamos que no esté expirado
            if ($consulta['expiracion'] > date('Y-m-d H:i:s')) {
                //si el formulario se ha validado
                if ($formGet->validarGlobal()) {
                    $consulta = DWESBaseDatos::obtenUsuarioPorToken($db, $_GET['token']);
                    if($consulta != ""){
                        //acciones para verificar user
                        DWESBaseDatos::verificaUsuario($db, $consulta['id']);

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
                            DWESBaseDatos::insertarToken($db, $consulta['id'], $token);

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

                        //recarga de la página para reload de la img del user
                        // header("Location: verify.php?verificado=si");
                        // die();

                        //estado verificado para pintar el OKEY
                        $estado = "verificado";
                        
                    }else{
                        $erroresForm['usuarioNoEncontrado'] = "Error, usuario no encontrado";
                    }
                }
            //token expirado
            }else{
                $estado = "con-link-no-valido";
            }
        //token no encontrado
        }else{
            $estado = "con-link-no-valido";
        }
        


    // ********** USER LLEGA SIN LINK DE VERIFICACIÓN **********
    }else{
        $estado = "sin-link";
        //si el formulario se ha validado
        if ($formNoGet->validarGlobal()) {
            $estado = "sin-link-enviado";
        }
    }
    

    // ********** INFO PARA EL TEMPLATE **********
    $tituloHead = "Verifica tu cuenta - SeriesBuddies";
    $estiloEspecifico = "./css/login.css";
    $scriptEspecifico = "";
    $content;

    // ********** COMIENZO BUFFER **********
    ob_start();
?>

    <h1 class="title title--form">Verificación</h1>

    <?php // ********** USER LLEGA CON LINK DE VERIFICACIÓN ********** ?>
    <?php if($estado == "con-link-valido"){ ?>
        <p class="extra-form-info">
            Un último paso <strong>¿Quieres que te recordemos en este dispositivo?</strong>
        </p>
        <?php $formGet->pintarGlobal(); ?>
        <?php foreach ($erroresForm as $value) { echo "<p class='error'>".$value."</p>";} ?> 
    <?php }else if($estado == "con-link-no-valido"){ ?>
        <p class="extra-form-info">
            <i class="fa-solid fa-circle-xmark xmark-form"></i>
            <br><br>
            <strong>Enlace no válido o expirado ¯\_(ツ)_/¯</strong>
            <br><br>
            ¿Ya tienes cuenta verificada? <a href="login.php" class="link-enphasis link-body">Login</a>
            <br>
            ¿Necesitas verificar tu cuenta? <a href="verify.php" class="link-enphasis link-body">Verifícala</a>
        </p>
    <?php }else if ($estado == "verificado"){?>
        <p class="extra-form-info">
            <i class="fa-sharp fa-solid fa-circle-check checkmark-form"></i>
            <br><br>
            <strong>¡Registro completado!</strong>
            <br><br>
            <a href="index.php" class="link-enphasis link-body">Ir al inicio</a>
        </p>


    <?php // ********** USER LLEGA SIN LINK DE VERIFICACIÓN ********** ?>
    <?php }else if ($estado == "sin-link"){?>
        <p class="extra-form-info">
            <strong>¿Necesitas otro enlace de verificación?</strong><br>
            Si no te ha llegado el correo de verificación o lo has borrado por error ¡No pasa nada! 
            Introduce tu email y te lo mandamos de nuevo =)
        </p>
        <?php $formNoGet->pintarGlobal(); ?>
        <p class="extra-form-info">
            ¿Ya tienes cuenta verificada? <a href="login.php" class="link-enphasis link-body">Login</a>
            <br>
            ¿Contraseña olvidada? <a href="recovery.php" class="link-enphasis link-body">Recupérala</a>
        </p>
    <?php }else if ($estado == "sin-link-enviado"){?>
        <p class="extra-form-info">
            <i class="fa-sharp fa-solid fa-circle-check checkmark-form"></i>
            <br><br>
            <strong>¡Correo de verificación enviado!</strong> Revisa tu bandeja de entrada.
        </p>
    <?php } ?>

<?php

    // ********** FIN BUFFER + LLAMADA AL TEMPLATE **********
    $content = ob_get_contents();
    ob_end_clean();
    require("template.php");
    
?>