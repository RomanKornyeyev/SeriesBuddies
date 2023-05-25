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

            //buscamos el user
            $consulta = DWESBaseDatos::obtenUsuarioPorMail($db, $_SESSION['correo']);

            foreach ($consulta as $key => $value) {
                $key." = ".$value."<br>";
            }

            //si no ha superado el límite de contacto (1/día)
            if ($consulta['ult_contacto'] <= date('Y-m-d H:i:s', strtotime('-1 day'))) {
                
                //mandamos mail de confirmación
                Mailer::sendEmail(
                    'no.reply.seriesbuddies@gmail.com',
                    'Una persona te quiere contactar: ',
                    'Asunto: '.$asunto->getValor().
                    '<br>Nombre: '.$nombre->getValor().
                    '<br>Email: '.$email->getValor().
                    '<br>Mensaje: '.$mensaje->getValor()
                );

                //estado enviado (para el HTML)
                $estado = 'enviado';

                //actualizamos la fecha de la última petición de contacto
                DWESBaseDatos::actualizarContactoStaff($db, $consulta['id'], date('Y-m-d H:i:s'));


            // --- si el user intenta contactar más de 1vez/día --- 
            }else{
                $erroresForm['limiteDiario'] = "Solo puedes ponerte en contacto con nosotros 1 vez por día. Próximo disponible: ".
                date('Y-m-d H:i:s', strtotime($consulta['ult_tkn_solicitado'] . ' +1 day')); 
            }
        }
    }
    
    // ********* INFO PARA EL TEMPLATE **********
    $tituloHead = "Contacto - SeriesBuddies";
    $estiloEspecifico = "./css/contact.css";
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
        <?php foreach ($erroresForm as $value) { echo "<p class='error'>".$value."</p>";} ?>
    <?php } else { ?>
        <p class="extra-form-info">
            <i class="fa-sharp fa-solid fa-circle-check checkmark-form"></i>
            <br><br>
            <strong>¡Mensaje enviado!</strong> Gracias por ponerte en contacto con nosotros, en caso de ser necesario, nos pondremos en contacto lo más pronto posible.
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