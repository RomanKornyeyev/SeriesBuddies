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
    $formulario = new Formulario("", Formulario::METHOD_POST, ["form"],        Formulario::VACIAR_NO,          "",                   array(
        //                       ====================================== COMÚN =======================================================================  //  ======================== ESPECÍFICO ========================
        //                  ¿Puede estar vacío? valor  name    label         clases-css-label         clases-css-wrapper       clases-css-input            tipoCampo    placeholder    regex
        $email = new Texto         (Atipo::NULL_NO, null, "email",     "Email",   ["label","label--text"],    ["input-wrapper"], ["input","shadow-lightgray"],  Texto::TYPE_TEXT, " ",  Texto::EMAIL_PATTERN),
        $nombre = new Texto        (Atipo::NULL_NO, null, "nombre",    "Texto corto",  ["label","label--text"],    ["input-wrapper"], ["input","shadow-lightgray"],  Texto::TYPE_TEXT, " ",  Texto::DEFAULT_PATTERN_25)
    // === SUBMIT ===
    // claseWrappSubmit                           idSubmit  nameSubm  txtSubmit  clseSubmit
    ), ["input-wrapper","input-wrapper--submit"], "enviar", "enviar", "ENVIAR", ["btn", "btn--primary", "shadow-lightgray"]);

    
    // ********* MAILER **********
    if ($formulario->validarGlobal()) {
        Mailer::sendEmail(
            $email->getValor(),
            "Prueba",
            $nombre->getValor()
            
        );
    }
    
    
    // ********* INFO PARA EL TEMPLATE **********
    $tituloHead = "Pruebas mailer";
    $estiloEspecifico = "./css/login.css";
    $scriptEspecifico = "";
    $content;

    // ********* COMIENZO BUFFER **********
    ob_start();
?>

    <h1 class="title title--form">Maileando</h1>
    <?php $formulario->pintarGlobal(); ?>

<?php

    // ********* FIN BUFFER + LLAMADA AL TEMPLATE **********
    $content = ob_get_contents();
    ob_end_clean();
    require("template.php");
    
?>