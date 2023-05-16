<?php

    require_once("../src/init.php");
    use form\claseMain\TMDB;
    
    $tmdb = new TMDB();

    $url = $tmdb->urlListadoGeneros();
    $response = $tmdb->peticionHTTP($url)['genres'];

    // ********* INFO PARA EL TEMPLATE **********
    $tituloHead = "Géneros - SeriesBuddies";
    $estiloEspecifico = "./css/genders.css";
    $scriptEspecifico = "./js/genders.js";
    $scriptLoadMode = "defer";
    $content;

    // ********* COMIENZO BUFFER **********
    ob_start();
?>
    <h1 class="title title--l text-align-center">GÉNEROS</h1>
    <div class="main__content">


        <?php
            // *** generación de cajas ***
            foreach ($response as $key => $value) {
            $url = $tmdb->urlSeriesGeneros($response[$key]['id']);
        ?>
            <!-- caja género -->
            <div class="box">
                <a href="./series.php?id=<?=$response[$key]['id']?>" class="box-body-wrapper">
                    <div class="box__body box__body--gender">
                        <img class="img-fit img-gender" src="./upload/generos/<?=$response[$key]['id']?>.png" alt="género" loading="lazy">
                        <div class="box-body__info">
                            <h2 class="title title-gender"><?=$response[$key]['name']?></h2>
                            <p class="text-white resultados" id="r-<?=$i?>" data-url="<?=$url?>">
                                <?php echo $resultados['total_results']?> resultados &gt;
                            </p>
                        </div>
                    </div>
                </a>
            </div>            
        <?php } ?>


    </div>

    <div class="pagination">
        <a href="#" class="btn btn--primary btn--sm">&lt;</a>
        <a href="#" class="btn btn--primary btn--sm">1</a>
        <a href="#" class="btn btn--primary btn--sm">&gt;</a>
    </div>
<?php

    // ********* FIN BUFFER + LLAMADA AL TEMPLATE **********
    $content = ob_get_contents();
    ob_end_clean();
    require("template.php");
    
?>