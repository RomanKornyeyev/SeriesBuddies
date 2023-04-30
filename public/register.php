<?php

    require_once("../src/init.php");
    use form\campo\Atipo;
    use form\campo\Fecha;
    use form\campo\Multiple;
    use form\campo\Numero;
    use form\campo\Texto;
    use form\campo\File;
    use form\claseMain\Formulario;

    // ================================= INICIALIZACIÓN DEL FORM =================================
    //                             ACTION            METHOD           clases-css-form   ¿Vaciar al validar?   atr-extra(para forms con img)   CAMPOS
    $formulario = new Formulario("",         Formulario::METHOD_POST, ["form"],        Formulario::VACIAR_NO,  Formulario::ATR_IMG,           array(
        //                         ====================================== COMÚN ==============================================================================  //  ======================== ESPECÍFICO ========================
        //                     ¿Puede estar vacío?  valor    name       label       clases-css-label         clases-css-wrapper  clases-css-input                  tipoCampo     placeholder     regex
        $nombre = new Texto        (Atipo::NULL_NO, null, "nombre",    "Nombre",  ["label","label--text"],    ["input-wrapper"], ["input","shadow-lightgray"],  Texto::TYPE_TEXT, " ",  Texto::DEFAULT_PATTERN_25),
        $email = new Texto         (Atipo::NULL_NO, null, "email",     "Email",   ["label","label--text"],    ["input-wrapper"], ["input","shadow-lightgray"],  Texto::TYPE_TEXT, " ",  Texto::EMAIL_PATTERN),
        $pass = new Texto          (Atipo::NULL_NO, null, "passwd",    "Password",["label","label--text"],    ["input-wrapper"], ["input","shadow-lightgray"],  Texto::TYPE_PSWD, " ",  Texto::DEFAULT_PATTERN_25),
        //                                                                                                                                                          f_ini             f_fin
        $fecha = new Fecha         (Atipo::NULL_SI, null, "fecha",     "Fecha",   ["label","label--text"],    ["input-wrapper"], ["input"],                     Fecha::NOW, Fecha::PLUS_ONE_WEEK),
        //                                                                                                                                                      auxGlobalWrapper        imgWrp            clsImg      imgDflt            accept           max size         ruta guardado
        $img = new File            (Atipo::NULL_SI, null, "img",       "",        ["label", "label--img"],["input-wrapper", "input-wrapper--img"], ["d-none"],  ["input-wrapper-aux-center"], ["profile-img"], ["img-fit"],File::IMG_DEFAULT, File::ACCEPT_BOTH, File::SIZE_LOW, File::RUTA_PERFIL)
    // === SUBMIT ===
    // claseWrappSubmit                           idSubmit  nameSubm  txtSubmit  clseSubmit
    ), ["input-wrapper","input-wrapper--submit"], "enviar", "enviar", "REGISTRARME", ["btn", "btn--primary", "shadow-lightgray"]);

    //si el formulario se ha validado
    if ($formulario->validarGlobal()) {
        //hace algo
        echo "correcto";
    }

    //guardado img (esta variable vendría de una BD, ej: id_user)
    $variable = 1;
    if ($formulario->validarGlobal()) {
        // mueve el archivo/img del campo name=img a:               ruta          nombre    ext
        //move_uploaded_file($_FILES[$img->getName()]['tmp_name'], $img->getRuta().$variable.".png");
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
    <!-- pintar global lleva implicito los errores personalizados -->
    <!-- necesario poner placerholder con un espacio vacío para un trick css -->
    <?php $formulario->pintarGlobal(); ?>
    <!-- user/pass incorrectas -->
    <?php if(isset($erroresForm['incorrecto'])) echo "<p class='error'>".$erroresForm['incorrecto']."</p>" ?>
    <div class="extra-form-info">
        ¿Ya eres buddy? <a href="login.php" class="link-enphasis link-body">Inicia sesión</a>
    </div>

<?php

    // ********* FIN BUFFER + LLAMADA AL TEMPLATE **********
    $content = ob_get_contents();
    ob_end_clean();
    require("template.php");
    
?>