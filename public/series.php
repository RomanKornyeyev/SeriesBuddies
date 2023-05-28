<?php

    // ***** INIT *****
    require_once("../src/init.php");

    // ***** API TMDB *****
    use clases\api_tmdb\TMDB;
    
    $tmdb = new TMDB();
    
    if (isset($_GET['pagina'])) {
        $paginaActual = $_GET['pagina'];
    } else {
        $paginaActual = 1;
    }
    
    (isset($_GET['id']))? $idGenero = $_GET['id']: $idGenero = 0;

    if (isset($_GET['buscador']) && !isset($_GET['id'])) {
        //saca series por nombre
        $serieBuscada = $_GET['buscador'];
        $response=$tmdb->getSeriesNombre($serieBuscada, $paginaActual);
        
        $totalPaginas = end($response);
        array_pop($response);

        $titulo = 'Resultados de  "'.$serieBuscada.'"';

        //paginación
        $paginaBase = "series";
        $argumentos = array(
            "buscador" => $_GET['buscador']
        );
        $paginacion = DWESBaseDatos::obtenPaginacion($paginaBase, $paginaActual, $totalPaginas, $argumentos);


    } else if (isset($_GET['id'])) {
        $nbGenero = $tmdb->getGeneroPrincipalOptimizado($idGenero)[$idGenero];
        $titulo = "Series de ".$nbGenero;


        $response = $tmdb->getSeriesGenero($idGenero, $paginaActual);
        $totalPaginas = end($response);
        array_pop($response);

        //paginación
        $paginaBase = "series";
        $argumentos = array(
            "id" => $idGenero
        );
        $paginacion = DWESBaseDatos::obtenPaginacion($paginaBase, $paginaActual, $totalPaginas, $argumentos);
    }

    // ********* INFO PARA EL TEMPLATE **********
    $tituloHead = "Series de ".$nbGenero." - SeriesBuddies";
    $estiloEspecifico = "./css/series.css";
    $scriptEspecifico = "";
    $scriptLoadMode = "";
    $content;

    // ********* COMIENZO BUFFER **********
    ob_start();
?>

    <nav class="nav-aux">
        <a href="./genders.php" class="primary-font primary-color">Géneros</a>
        <span class="primary-font color-white">&gt;</span>
        <a href="./series.php?id=<?=$idGenero?>" class="primary-font primary-color"><?=$nbGenero?></a>
    </nav>

    <h1 class="title title--l text-align-center"><?=$titulo?></h1>

    <div class="pagination pagination--plus-response">
        <a href="./genders.php" class="btn"><i class="fa-solid fa-arrow-left"></i> &nbsp; Géneros</a>
        <?=$paginacion?>
    </div>
    <?php foreach ($response as $serie) { ?>
    <?php $listadoBuddies = $db->obtenPrimerosBuddiesSerie($db, $serie['id']); ?>
    <div class="card">
        <a class="card__serie-img" href="./feed.php?id=<?=$serie['id']?>&id_genero=<?=$idGenero?>">
            <img class="img-fit" src="<?=$serie['posterImage']?>" alt="serie-img">
        </a>
        <div class="card__serie-info">
            <div class="header__serie">
                <a class="title title--serie" href="./feed.php?id=<?=$serie['id']?>&id_genero=<?=$idGenero?>"><?=$serie['serieTitle']?></a>
                <!-- <div class="extra-actions">
                    <a href="#" class="btn btn--secondary btn--bold">Modificar</a>
                    <a href="#" class="btn btn--error btn--bold">Eliminar</a>
                </div> -->
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
                <a href="./feed.php?id=<?=$serie['id']?>&id_genero=<?=$idGenero?>">
                    <?php $totalRespuestas = $db->obtenTotalRespuestas ($db, $serie['id']) ?>
                    <p class="info info--serie"><?=$totalRespuestas?> comentarios &gt;</p>
                </a>
            </div>
        </div>
    </div>

    <?php } ?>

    <div class="pagination pagination--plus-response">
        <a href="./genders.php" class="btn"><i class="fa-solid fa-arrow-left"></i> &nbsp; Géneros</a>
        <?=$paginacion?>
    </div>
<?php

// ********* FIN BUFFER + LLAMADA AL TEMPLATE **********
$content = ob_get_contents();
ob_end_clean();
require("template.php");

?>
