<?php

    require_once("../src/init.php");
    use form\claseMain\TMDB;
    
    $tmdb = new TMDB();

    $url = $tmdb->urlListadoGeneros();
    $response = $tmdb->peticionHTTP($url)['genres'];

    // ********* INFO PARA EL TEMPLATE **********
    $tituloHead = "Géneros - SeriesBuddies";
    $estiloEspecifico = "./css/genders.css";
    $scriptEspecifico = "";
    $content;

    // ********* COMIENZO BUFFER **********
    ob_start();
?>
    <h1 class="title title--l text-align-center">GÉNEROS</h1>
    <div class="main__content">
        
        <?php foreach ($response as $key => $value) {
            $url = $tmdb->urlSeriesGeneros($response[$key]['id']); 
            $resultados = $tmdb->peticionHTTP($url);
        ?>
            <div class="box">
                <a href="./series.php?id=<?=$response[$key]['id']?>" class="box-body-wrapper">
                    <div class="box__body box__body--gender">
                        <img class="img-fit img-gender" src="./upload/stranger_things.png" alt="género">
                        <div class="box-body__info">
                            <h2 class="title title-gender"><?=$response[$key]['name']?></h2>
                            <p class="text-white"> <?php echo $resultados['total_results']?> resultados &gt;</p>
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
