<?php

    // ***** INIT *****
    require_once("../src/init.php");
    $tarjetas = DWESBaseDatos::obtenTarjetasIndex($db);
    
    if (!$sesionIniciada) {
        $redirecciones['ruta'][0] = './login.php';
        $redirecciones['ruta'][1] = './register.php';
        $redirecciones['boton'][0] = 'Inicia sesión';
        $redirecciones['boton'][1] = 'Hazte buddy';
    } else {
        $redirecciones['ruta'][0] = './genders.php';
        $redirecciones['ruta'][1] = './buddies.php';
        $redirecciones['boton'][0] = 'Géneros';
        $redirecciones['boton'][1] = 'Buddies';
    }

    // ********* INFO PARA EL TEMPLATE **********
    $tituloHead = "SeriesBuddies";
    $estiloEspecifico = "./css/index.css";
    $scriptEspecifico = "./js/index.js";
    $scriptLoadMode = "defer";
    $content;

    // ********* COMIENZO BUFFER **********
    ob_start();
?>
    <!-- img hero -->
    <div class="hero" id="hero">
        <img class="img-fit" src="./upload/hero-1.png" alt="seriesbuddies">
        <!-- comment top -->
        <div class="row row--top" id="row-top">
            <div class="row__user">
                <div class="user__img user__img--top">
                    <img class="img-fit img-user-landing" src="./<?=$tarjetas[0]['img']?>" alt="user">
                </div>
                <div class="user__medals">
                    <p class="post__date"><?=$tarjetas[0]['nombre']?></p>
                </div>
            </div>
            <div class="row__post row__post--top">
                <p class="post__date"><?=$tarjetas[0]['fecha']?></p>
                <p class="post__content"><?=$tarjetas[0]['contenido']?></p>
            </div>
        </div>
        <!-- comment bottom -->
        <div class="row row--bottom" id="row-bottom">
            <div class="row__user">
                <div class="user__img user__img--bottom">
                    <img class="img-fit img-user-landing" src="./<?=$tarjetas[1]['img']?>" alt="user">
                </div>
                <div class="user__medals">
                <p class="post__date"><?=$tarjetas[1]['nombre']?></p>
                </div>
            </div>
            <div class="row__post row__post--bottom">
                <p class="post__date"><?=$tarjetas[1]['fecha']?></p>
                <p class="post__content"><?=$tarjetas[1]['contenido']?></p>
            </div>
        </div>
    </div>
    <!-- texto -->
    <div class="landing-info">
        <div class="landing-info__content">
            <h1 class="title title--landing">
                INTERCAMBIA IDEAS SOBRE
                <br>
                <span class="font-size-ultra-big">
                    TUS SERIES FAVORITAS
                </span>
            </h1>
            <div class="landing-buttons">
                <a href="<?=$redirecciones['ruta'][0]?>" class="btn btn--primary"><?=$redirecciones['boton'][0]?></a>
                <a href="<?=$redirecciones['ruta'][1]?>" class="btn btn--outline"><?=$redirecciones['boton'][1]?></a>
            </div>
        </div>
    </div>

<?php

    // ********* FIN BUFFER + LLAMADA AL TEMPLATE **********
    $content = ob_get_contents();
    ob_end_clean();
    require("template_footerless.php");

?>