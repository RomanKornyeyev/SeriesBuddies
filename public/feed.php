<?php
    require_once("../src/init.php");
    use clases\api_tmdb\TMDB;
    
    $tmdb = new TMDB();
    
    $idSerie = $_GET['id'];

    //Guardamos la pagina actual de donde nos encontramos
    if (isset($_GET['pagina'])) {
        $paginaActual = $_GET['pagina'];
    } else {
        $paginaActual = 1;
    }

    //Recogemos la informacion de la serie por su ID
    $response = $tmdb->getSerieID($idSerie);

    //Comentarios por pagina a mostrar
    $registrosPagina = DWESBaseDatos::REGISTROS_POR_PAGINA;

    //Registro/comentario desde el que empezar a recorrer la tabla
    $registroInicial = ($paginaActual-1)*$registrosPagina;

    //Nombre, comentario, fecha e imagen del usuario + id de la serie en determinado rango
    $comentarios = $db->getRespuestasSerie($db, $idSerie, $registroInicial);

    //Total de los comentarios que hay de esa serie
    $totalRegistros = $db->getTotalRespuestas($db, $idSerie);

    //Total de paginas que hay que mostrar
    $totalPaginas   = ceil($totalRegistros / $registrosPagina);

    //Devuelve la primera y la ultima pagina disponible
    $limites = $db->getLimitesPaginacion($paginaActual, $totalPaginas);

    //Fotos de los primeros 5 usuarios que han comentado esta serie
    $listadoBuddies = $db->getPrimerosBuddiesSerie($db, $idSerie);

    // ********* INFO PARA EL TEMPLATE **********
    $tituloHead = $response['serieTitle']." - SeriesBuddies";
    $estiloEspecifico = "./css/feed.css";
    $scriptEspecifico = "";
    $content;

    // ********* COMIENZO BUFFER **********
    ob_start();
?>
    <div class="card">
        <div class="card__serie-img">
            <img class="img-fit" src="<?=$response['backdrop']?>" alt="serie-img">
            <h2 class="title title--serie"><?=$response['serieTitle']?></h2>
        </div>
        <div class="card__serie-info">
            <div class="info info--serie">
                <p class="text text--serie"><?=$response['seriePlot']?></p>
                <?php foreach ($response['serieGenres'] as $key => $value) { ?>
                    <a href="./series.php?id=<?=$response['serieGenres'][$key]['id']?>&nombre=<?=$response['serieGenres'][$key]['name']?>" class="btn btn--outline_filter">#<?=$response['serieGenres'][$key]['name']?></a>
                <?php } ?>
            </div>
            <a href="./buddies.php?id=<?=$idSerie?>" class="links__buddy">
                <div class="icon">
                    <?php foreach ($listadoBuddies as $key => $value) { ?>
                        <div class="icon__buddy img-user-post"><img class="img-fit" src="<?=$listadoBuddies[$key]['img']?>" alt="serie-img"></div>
                    <?php } ?>
                </div>
                <p class="info info--buddy">Ver todos los buddies &gt;</p>
            </a> 
        </div>
    </div>

    <?php foreach ($comentarios as $key => $value) { ?>
        <div class="card">
            <div class="card__post">
                <div class="card__post-img">
                    <div class="img-user-post">
                        <img class="img-fit" src="<?=$comentarios[$key]['img']?>" alt="serie-img">
                    </div>
                    <h2 class="title title--user"><?=$comentarios[$key]['nombre']?></h2>
                    <div class="icon">
                        <div class="icon__chip"></div>
                        <div class="icon__chip"></div>
                        <div class="icon__chip"></div>
                    </div>
                    <div class="user--responsive">
                        <h2 class="title title--user"><?=$comentarios[$key]['nombre']?></h2>
                        <div class="icon">
                            <div class="icon__chip"></div>
                            <div class="icon__chip"></div>
                            <div class="icon__chip"></div>
                        </div>
                    </div>
                </div>
                <div class="card__post-comment">
                    <div class="info info--comment">
                        <div class="date-post">Publicado el <?=$comentarios[$key]['fecha']?></div>
                        <div class="admin-area">
                            <a href="" class="btn btn--secondary btn--sm btn--bold">Editar</a>
                            <a href="" class="btn btn--error btn--sm btn--bold">Eliminar</a>
                        </div>
                    </div>
                    <p class="text text--comment"><?=$comentarios[$key]['contenido']?></p>
                </div>
            </div>
        </div>
    <?php } ?>
    
    <div class="pagination">
        <?php if ($totalPaginas > 1) {
            //Te saca el boton de ir hacia atras si no estas en la primera pagina
            if ($paginaActual != 1) { ?>
                <a href="./feed.php?id=<?=$idSerie?>&pagina=<?=($paginaActual-1)?>" class="btn btn--primary btn--sm">&lt;</a>
            <?php }

            //Mostramos la primera pagina y los ...
            if ($limites['primera'] != 1) { ?>
                <a href="./feed.php?id=<?=$idSerie?>&pagina=1" class="btn btn--outline btn--sm">1</a>
                <span class="btn btn--outline btn--sm">...</span>
            <?php }

            //Te pinta el boton de la pagina en la que estas, las anteriores y las siguientes (intervalo de 5)
            for ($i=$limites['primera']; $i <= $limites['ultima']; $i++) { 
                if ($paginaActual == $i) { ?>
                    <a href="./feed.php?id=<?=$idSerie?>&pagina=<?=$paginaActual?>" class="btn btn--primary btn--sm"><?=$paginaActual?></a>
                <?php } else { ?>
                <a href="/feed.php?id=<?=$idSerie?>&pagina=<?=$i?>" class="btn btn--outline btn--sm"><?=$i?></a>
            <?php }
            }
            
            //Saca la ultima página que hay en el registro
            if ($limites['ultima'] != $totalPaginas) { ?>
                <span class="btn btn--outline btn--sm">...</span>
                <a href="./feed.php?id=<?=$idSerie?>&pagina=<?=$totalPaginas?>" class="btn btn--outline btn--sm"><?=$totalPaginas?></a>;
            <?php }

            //Te saca el boton de ir hacia adelante si no estas en la última pagina
            if ($paginaActual != $totalPaginas) { ?>
                <a href="./feed.php?id=<?=$idSerie?>&pagina=<?=($paginaActual+1)?>" class="btn btn--primary btn--sm">&gt;</a>
            <?php } ?>
            
        <?php } ?>
    </div>

<?php

// ********* FIN BUFFER + LLAMADA AL TEMPLATE **********
$content = ob_get_contents();
ob_end_clean();
require("template.php");

?>
