<?php

    require_once("../src/init.php");
    use form\campo\Atipo;
    use form\campo\Fecha;
    use form\campo\Multiple;
    use form\campo\Numero;
    use form\campo\Texto;
    use form\campo\File;
    use form\claseMain\Formulario;

    $nombreDefault = '';
    $emailDefault = '';
    if ($sesionIniciada) {
        $nombreDefault = $_SESSION['nombre'];
        $emailDefault = $_SESSION['correo'];
    }

    // ========================================= FORM DE CONTACTO =========================================
    //                             ACTION            METHOD           clases-css-form   ¿Vaciar al validar?   atr-extra(para forms con img)   CAMPOS
    $formulario = new Formulario("", Formulario::METHOD_POST, ["form"],        Formulario::VACIAR_NO,          "",                   array(
        //                       ====================================== COMÚN =======================================================================  //  ======================== ESPECÍFICO ========================
        //                  ¿Puede estar vacío? valor  name          label         clases-css-label       clases-css-wrapper       clases-css-input            tipoCampo    placeholder    regex
        $nombre =  new Texto (Atipo::NULL_SI, $nombreDefault, "nombre",    "Nombre",  ["label","label--text"],    ["input-wrapper"],  ["input","shadow-lightgray"],  Texto::TYPE_TEXT, " ",  Texto::DEFAULT_PATTERN_25),
        $email =   new Texto (Atipo::NULL_NO, $emailDefault,  "email",     "Email",   ["label","label--text"],    ["input-wrapper"],  ["input","shadow-lightgray"],  Texto::TYPE_TEXT, " ",  Texto::EMAIL_PATTERN),
        $asunto =  new Texto (Atipo::NULL_SI, null,           "asunto",    "Asunto",  ["label","label--text"],    ["input-wrapper"],  ["input","shadow-lightgray"],  Texto::TYPE_TEXT, " ",  Texto::DEFAULT_PATTERN_25),
        $mensaje = new Texto (Atipo::NULL_NO, null,           "mensaje",   "Escribe aquí tu mensaje...",  ["label","label--text"],    ["input-wrapper"], ["input","shadow-lightgray"],       Texto::TYPE_TAREA, " ",  Texto::DEFAULT_PATTERN_500)

    // === SUBMIT ===
    // claseWrappSubmit                           idSubmit  nameSubm  txtSubmit  clseSubmit
    ), ["input-wrapper","input-wrapper--submit"], "enviar", "enviar", "Enviar", ["btn", "btn--primary", "shadow-lightgray"]);


    // ********** EL USER NO TIENE LA SESIÓN INICIADA **********
    if (!$sesionIniciada) {
        $estado = 'sesion-no-iniciada';


    // ********** EL USER SÍ TIENE LA SESIÓN INICIADA **********
    }else{
        $estado = 'no-enviado';
        //si el formulario se ha validado
        if ($formulario->validarGlobal()) {
            
            if ($consulta['ult_tkn_solicitado'] <= date('Y-m-d H:i:s', strtotime('-1 day'))) {
                # code...
            }
            //mandamos mail de confirmación
            Mailer::sendEmail(
                'no.reply.seriesbuddies@gmail.com',
                'Una persona te quiere contactar: ',
                'Asunto: '.$asunto->getValor().
                '<br>Nombre: '.$nombre->getValor().
                '<br>Email: '.$email->getValor().
                '<br>Mensaje: '.$mensaje->getValor()         
            );
            $estado = 'enviado';
        }
    }
    
    // ********* INFO PARA EL TEMPLATE **********
    $tituloHead = "Contacto - SeriesBuddies";
    $estiloEspecifico = "./css/login.css";
    $scriptEspecifico = "";
    $content;

    // ********* COMIENZO BUFFER **********
    ob_start();
?>
    <h1 class="title title--form">Contacto</h1>
    <?php if ($estado == 'sesion-no-iniciada') { ?>
        <p class="extra-form-info">
            Si quieres contactar con nosotros, dejarnos sugerencias o preguntarnos algo, no lo dudes:
            <br><br>
            <a href="login.php" class="link-enphasis link-body">Inicia sesión</a> &nbsp;&nbsp;|&nbsp;&nbsp; <a href="register.php" class="link-enphasis link-body">Regístrate</a>
        </p>
    <?php } else if ($estado == 'no-enviado') { ?>
        
        <!-- pintar global lleva implicito los errores personalizados -->
        <!-- necesario poner placerholder con un espacio vacío para un trick css -->
        <p class="extra-form-info text-align-justify">
            Si quieres dejarnos alguna sugerencia a mejorar sobre SeriesBuddies ponte en contacto con nosotros mediante este formulario.
        </p>
        <?php $formulario->pintarGlobal(); ?>
    <?php } else { ?>
        <p class="extra-form-info">
            <i class="fa-sharp fa-solid fa-circle-check checkmark-form"></i>
            <br><br>
            <strong>¡Mensaje enviado!</strong> Nos pondremos en contacto lo más pronto posible.
            <br><br>
            <a href="index.php" class="link-enphasis link-body">Ir al inicio</a>
        </p>
    <?php } ?>
    

<?php

    // ********* FIN BUFFER + LLAMADA AL TEMPLATE **********
    $content = ob_get_contents();
    ob_end_clean();
    require("template.php");
    
?>