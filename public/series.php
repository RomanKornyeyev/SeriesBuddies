<?php

    // ***** INIT *****
    require_once("../src/init.php");

    // ***** API TMDB *****
    use clases\api_tmdb\TMDB;
    
    $tmdb = new TMDB();
    
    $idGenero = $_GET['id'];
    $nbGenero = $_GET['nombre'];
    if (isset($_GET['pagina'])) {
        $paginaActual = $_GET['pagina'];
    } else {
        $paginaActual = 1;
    }
    
    $response = $tmdb->getSeriesGenero($idGenero, $paginaActual);
    $totalPaginas = end($response);
    array_pop($response);

    //Devuelve la primera y la ultima pagina disponible
    $limites = $db->obtenLimitesPaginacion($paginaActual, $totalPaginas);
   


    // ********* INFO PARA EL TEMPLATE **********
    $tituloHead = "Series de ".$nbGenero." - SeriesBuddies";
    $estiloEspecifico = "./css/series.css";
    $scriptEspecifico = "";
    $content;

    // ********* COMIENZO BUFFER **********
    ob_start();
?>

    <h1 class="title title--l text-align-center">Series de <?=$nbGenero?></h1>

    <?php foreach ($response as $serie) { ?>
    <?php $listadoBuddies = $db->obtenPrimerosBuddiesSerie($db, $serie['id']); ?>
    <div class="card">
        <a class="card__serie-img" href="./feed.php?id=<?=$serie['id']?>">
            <img class="img-fit" src="<?=$serie['posterImage']?>" alt="serie-img">
        </a>
        <div class="card__serie-info">
            <div class="header__serie">
                <a class="title title--serie" href="./feed.php?id=<?=$serie['id']?>"><?=$serie['serieTitle']?></a>
                <div class="extra-actions">
                    <a href="#" class="btn btn--secondary btn--bold">Modificar</a>
                    <a href="#" class="btn btn--error btn--bold">Eliminar</a>
                </div>
            </div>
            <div class="body__serie">
                <p class="text text--serie"><?=$serie['seriePlot']?></p>
            </div>
            <div class="footer__serie">
                <a href="./buddies.php?id-serie=<?=$serie['id']?>">
                <?php foreach ($listadoBuddies as $buddie) { ?>
                    <div class="icon__buddy img-user-post">
                        <img class="img-fit" src="<?=$buddie['img']?>" alt="serie-img">
                    </div>
                <?php } ?>
                    <p class="info info--serie">Super buddies</p>
                </a>
                <a href="./feed.php?id=<?=$serie['id']?>">
                    <?php $totalRespuestas = $db->obtenTotalRespuestas ($db, $serie['id']) ?>
                    <p class="info info--serie"><?=$totalRespuestas?> comentarios &gt;</p>
                </a>
            </div>
        </div>
    </div>

    <?php } ?>

    <div class="pagination">
        <?php if ($totalPaginas > 1) {
            //Te saca el boton de ir hacia atras si no estas en la primera pagina
            if ($paginaActual != 1) { ?>
                <a href="./series.php?id=<?=$idGenero?>&nombre=<?=$nbGenero?>&pagina=<?=($paginaActual-1)?>" class="btn btn--primary btn--sm">&lt;</a>
            <?php }

            //Mostramos la primera pagina y los ...
            if ($limites['primera'] != 1) { ?>
                <a href="./series.php?id=<?=$idGenero?>&nombre=<?=$nbGenero?>&pagina=1" class="btn btn--outline btn--sm">1</a>
                <span class="btn btn--outline btn--sm">...</span>
            <?php }

            //Te pinta el boton de la pagina en la que estas, las anteriores y las siguientes (intervalo de 5)
            for ($i=$limites['primera']; $i <= $limites['ultima']; $i++) { 
                if ($paginaActual == $i) { ?>
                    <a href="./series.php?id=<?=$idGenero?>&nombre=<?=$nbGenero?>&pagina=<?=$paginaActual?>" class="btn btn--primary btn--sm"><?=$paginaActual?></a>
                <?php } else { ?>
                <a href="./series.php?id=<?=$idGenero?>&nombre=<?=$nbGenero?>&pagina=<?=$i?>" class="btn btn--outline btn--sm"><?=$i?></a>
            <?php }
            }
            
            //Saca la ultima página que hay en el registro
            if ($limites['ultima'] != $totalPaginas) { ?>
                <span class="btn btn--outline btn--sm">...</span>
                <a href="./series.php?id=<?=$idGenero?>&nombre=<?=$nbGenero?>&pagina=<?=$totalPaginas?>" class="btn btn--outline btn--sm"><?=$totalPaginas?></a>;
            <?php }

            //Te saca el boton de ir hacia adelante si no estas en la última pagina
            if ($paginaActual != $totalPaginas) { ?>
                <a href="./series.php?id=<?=$idGenero?>&nombre=<?=$nbGenero?>&pagina=<?=($paginaActual+1)?>" class="btn btn--primary btn--sm">&gt;</a>
            <?php } ?>
            
        <?php } ?>
    </div>
<?php

// ********* FIN BUFFER + LLAMADA AL TEMPLATE **********
$content = ob_get_contents();
ob_end_clean();
require("template.php");

?>
