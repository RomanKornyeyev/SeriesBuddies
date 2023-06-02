<?php
    
    // ***** INIT *****
    require_once("../src/init.php");
    
    // ***** MAILER *****
    use clases\mailer\Mailer;

    // ***** FORM *****
    use clases\form\campo\Atipo;
    use clases\form\campo\Fecha;
    use clases\form\campo\Multiple;
    use clases\form\campo\Numero;
    use clases\form\campo\Texto;
    use clases\form\campo\File;
    use clases\form\claseMain\Formulario;

    // ========================================= FORM RECOVERY (LINK) =========================================
    $formGet = new Formulario("", Formulario::METHOD_POST, ["form"],        Formulario::VACIAR_NO,          "",                   array(
        $pass = new Texto       (Atipo::NULL_NO, null,"contra","Password",["label","label--text"],    ["input-wrapper"],  ["input","shadow-lightgray"],  Texto::TYPE_PSWD, " ",  Texto::DEFAULT_PSWD),
        $passDos = new Texto       (Atipo::NULL_NO, null,"contraDos","Password",["label","label--text"],    ["input-wrapper"],  ["input","shadow-lightgray"],  Texto::TYPE_PSWD, " ",  Texto::DEFAULT_PSWD)
    ), ["input-wrapper","input-wrapper--submit"], "enviar", "enviar", "CAMBIAR CONTRASEÑA", ["btn", "btn--primary", "shadow-lightgray"]);

    // ================================= FORM RECOVERY (EMAIL) =================================
    $formNoGet = new Formulario("", Formulario::METHOD_POST, ["form"],        Formulario::VACIAR_NO,          "",                   array(
        $email = new Texto      (Atipo::NULL_NO, null,"email", "Email",   ["label","label--text"],    ["input-wrapper"],  ["input","shadow-lightgray"],  Texto::TYPE_TEXT, " ",  Texto::EMAIL_PATTERN)
    ), ["input-wrapper","input-wrapper--submit"], "enviar", "enviar", "RECUPERAR", ["btn", "btn--primary", "shadow-lightgray"]);

    $estado = "sin-link";

    //si tiene sesión iniciada, al index
    if ($sesionIniciada) {
        header("Location: index.php");
        die();
    }else{
        // ********** USER LLEGA CON LINK DE VERIFICACIÓN **********
        if (isset($_GET['token'])) {
            //comprobamos que el token exista
            $consulta = DWESBaseDatos::obtenToken($db, $_GET['token']);
            if($consulta != ""){
                $estado = "con-link-valido";
                //comprobamos que no esté expirado
                if ($consulta['expiracion'] > date('Y-m-d H:i:s')) {
                    //obtenemos el user vinculado al token
                    $consulta = DWESBaseDatos::obtenUsuarioPorToken($db, $_GET['token']);
                    //comprobamos si el usuario está verificado
                    if ($consulta['verificado'] == DWESBaseDatos::VERIFICADO_SI) {
                        //si el formulario se ha validado
                        if ($formGet->validarGlobal()) {
                            //si las contraseñas son iguales
                            if ($pass->getValor() == $passDos->getValor()) {
                                //eliminamos posibles tokens residuales
                                DWESBaseDatos::eliminaTokensUsuario($db, $consulta['id']);
                                
                                //actualizamos la contraseña
                                DWESBaseDatos::actualizarContra($db, $consulta['id'], password_hash($pass->getValor(), PASSWORD_DEFAULT));
                    
                                //mandamos un mail de confirmación
                                Mailer::sendEmail(
                                    $consulta['correo'],
                                    "Contraseña modificada - SeriesBuddies",
                                    "Hola ".$consulta['nombre']." tu contraseña ha sido modificada correctamente."                
                                );
                    
                                //estado verificado para pintar el OKEY
                                $estado = "contra-modificada-exito";
                            //si las contraseñas no coinciden
                            } else {
                                $erroresForm['noCoincide'] = "Las contraseñas deben coincidir.";
                            }
                        }
                    // --- usuario no verificado --- 
                    }else{
                        //estado verificado para pintar el OKEY
                        $estado = "no-verificado";
                    }
                // --- token expirado --- 
                }else{
                    $estado = "con-link-no-valido";
                }
            // --- token no encontrado --- 
            }else{
                $estado = "con-link-no-valido";
            }
            


        // ********** USER LLEGA SIN LINK DE VERIFICACIÓN **********
        }else{
            $estado = "sin-link";
            //si el formulario se ha validado
            if ($formNoGet->validarGlobal()) {
                //si existe el user
                $consulta = DWESBaseDatos::obtenUsuarioPorMail($db, $email->getValor());
                if ($consulta != "") {
                    //y está verificado
                    if ($consulta['verificado'] == DWESBaseDatos::VERIFICADO_SI) {
                        //miramos si ha solicitado ya un token hoy (max 1/dia)
                        if ($consulta['ult_tkn_solicitado'] <= date('Y-m-d H:i:s', strtotime('-1 day'))) {

                            //elimina todos los tokens del usuario
                            DWESBaseDatos::eliminaTokensUsuario($db, $consulta['id']);

                            //genera e inserta un nuevo token
                            $token = bin2hex(openssl_random_pseudo_bytes(DWESBaseDatos::LONG_TOKEN));
                            DWESBaseDatos::insertarToken($db, $consulta['id'], $token);

                            //actualizamos la fecha de la solicitud del token (limite 1/día)
                            DWESBaseDatos::actualizarSolicitudTkn($db, $consulta['id'], date('Y-m-d H:i:s'));

                            //manda un mail de confirmación
                            Mailer::sendEmail(
                                $email->getValor(),
                                "Cambia tu contraseña - SeriesBuddies",
                                "Hola ".$consulta['nombre'].", has solicitado cambiar tu contraseña. Puedes cambiarla en el siguiente enlace: 
                                <a target='_blank' href='".DWESBaseDatos::RUTA_DOMINIO_BASE."/recovery.php?token=".$token."'>CAMBIAR MI CONTRASEÑA</a>"                
                            );

                            $estado = "sin-link-enviado";

                        // --- superado el límite de peticiones (1/día) --- 
                        }else{
                            $erroresForm['demasiadosIntentos'] = "Ya te hemos enviado un enlace de verificación, revisa tu bandeja de entrada, no puedes solicitar otro hasta ".
                            date('Y-m-d H:i:s', strtotime($consulta['ult_tkn_solicitado'] . ' +1 day')); 
                        }
                    // --- el usuario NO está verificado --- 
                    }else{
                        $erroresForm['noVerificado'] = "El usuario ".$consulta['correo']." no está verificado.
                        Por favor <a href='./verify.php' class='underline'>verifícalo</a>.";
                    }
                // --- user no existe --- 
                }else{
                    $erroresForm['noExiste'] = "Este usuario no existe";
                }
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


    <h1 class="title title--form">Recovery</h1>

    <?php // ********** USER LLEGA SIN LINK DE VERIFICACIÓN ********** ?>
    <?php if($estado == "sin-link"){ ?>
        <p class="extra-form-info">
            <b>¿Contraseña olvidada?</b> No pasa nada
            <br>
            Déjanos tu email y te enviaremos un enlace para recuperar tu contraseña =)
        </p>
        <?php $formNoGet->pintarGlobal(); ?>
        <?php foreach ($erroresForm as $value) { echo "<p class='error'>".$value."</p>";} ?>
        <div class="extra-form-info">
            ¿La has recordado? <a href="login.php" class="link-enphasis link-body">Inicia sesión</a>
            <br>
            ¿Necesitas verificar tu cuenta? <a href="verify.php" class="link-enphasis link-body">Verifícala</a>
        </div>
    <?php }else if($estado == "sin-link-enviado"){ ?>
        <p class="extra-form-info">
            <i class="fa-sharp fa-solid fa-circle-check checkmark-form"></i>
            <br><br>
            <strong>¡Correo de cambio de contraseña enviado!</strong> Revisa tu bandeja de entrada.
        </p>


    <?php // ********** USER LLEGA CON LINK DE VERIFICACIÓN ********** ?>    
    <?php }else if($estado == "con-link-valido"){ ?>
        <p class="extra-form-info">
            Introduce tu nueva contraseña:
        </p>
        <?php $formGet->pintarGlobal(); ?>
        <?php foreach ($erroresForm as $value) { echo "<p class='error'>".$value."</p>";} ?>
    <?php }else if($estado == "con-link-no-valido"){ ?>
        <p class="extra-form-info">
            <i class="fa-solid fa-circle-xmark xmark-form"></i>
            <br><br>
            <strong>Enlace no válido o expirado ¯\_(ツ)_/¯</strong>
            <br><br>
            Volver a <a href="recovery.php" class="link-enphasis link-body">enviar el enlace</a>
        </p>
    <?php }else if ($estado == "no-verificado"){?>
        <p class="extra-form-info">
            <i class="fa-solid fa-circle-xmark xmark-form"></i>
            <br><br>
            <strong>Este usuario no está verificado ¯\_(ツ)_/¯</strong>
            <br><br>
            Consulta tu email para encontrar el enlace de verificación o bien 
            <a href="verify.php" class="link-enphasis link-body">vuelve a enviar el email de verificación</a>
        </p>
    <?php }else if ($estado == "contra-modificada-exito"){?>
        <p class="extra-form-info">
            <i class="fa-sharp fa-solid fa-circle-check checkmark-form"></i>
            <br><br>
            <strong>¡Contraseña cambiada!</strong>
            <br><br>
            <a href="login.php" class="link-enphasis link-body">Iniciar sesión</a>
        </p>
    <?php }?>
    

<?php

    // ********* FIN BUFFER + LLAMADA AL TEMPLATE **********
    $content = ob_get_contents();
    ob_end_clean();
    require("template.php");
    
?>