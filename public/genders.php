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
    $scriptLoadMode = "";
    $content;

    // ********* COMIENZO BUFFER **********
    ob_start();
?>
    <h1 class="title title--l text-align-center">GÉNEROS</h1>
    <div class="main__content">
        <?php
            $i = 0;
            foreach ($response as $key => $value) {
            $url = $tmdb->urlSeriesGeneros($response[$key]['id']);
        ?>
            <!-- caja género -->
            <div class="box">
                <a href="./series.php?id=<?=$response[$key]['id']?>" class="box-body-wrapper">
                    <div class="box__body box__body--gender">
                        <img class="img-fit img-gender" src="./upload/generos/<?=$response[$key]['id']?>.png" alt="género">
                        <div class="box-body__info">
                            <h2 class="title title-gender"><?=$response[$key]['name']?></h2>
                            <p class="text-white" id="r-<?=$i?>"> <?php echo $resultados['total_results']?> resultados &gt;</p>
                        </div>
                    </div>
                </a>
            </div>
            <!-- petición AJAX para resultados (optimización de carga) -->
            <script>
                elemento = document.getElementById("r-<?php echo $i?>");
                url = "<?php echo $url?>";
                contarResultados(elemento, url);
            </script>
        <?php
            $i++; 
            }
        ?>

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