<?php

    require_once("../src/init.php");
    use form\campo\Atipo;
    use form\campo\Fecha;
    use form\campo\Multiple;
    use form\campo\Numero;
    use form\campo\Texto;
    use form\campo\File;
    use form\claseMain\Formulario;

    // ================================= FORM RECOVERY =================================
    $formulario = new Formulario("", Formulario::METHOD_POST, ["form"],        Formulario::VACIAR_NO,          "",                   array(
        $email = new Texto      (Atipo::NULL_NO, null,"email", "Email",   ["label","label--text"],    ["input-wrapper"],  ["input","shadow-lightgray"],  Texto::TYPE_TEXT, " ",  Texto::EMAIL_PATTERN)
    ), ["input-wrapper","input-wrapper--submit"], "enviar", "enviar", "RECUPERAR", ["btn", "btn--primary", "shadow-lightgray"]);

    $estado = "sin-enviar";

    //si tiene sesión iniciada, al index
    if ($sesionIniciada) {
        header("Location: index.php");
        die();
    }else{
        //si el formulario se ha validado
        if ($formulario->validarGlobal()) {
            //hace algo
            echo "correcto";
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
    <p class="extra-form-info">
        <b>¿Contraseña olvidada?</b> No pasa nada
        <br>
        Déjanos tu email y te enviaremos un enlace para recuperar tu contraseña =)
    </p>
    <?php $formulario->pintarGlobal(); ?>
    <div class="extra-form-info">
        ¿La has recordado? <a href="login.php" class="link-enphasis link-body">Inicia sesión</a>
        <br>
        ¿Necesitas verificar tu cuenta? <a href="verify.php" class="link-enphasis link-body">Verifícala</a>
    </div>

<?php

    // ********* FIN BUFFER + LLAMADA AL TEMPLATE **********
    $content = ob_get_contents();
    ob_end_clean();
    require("template.php");
    
?>