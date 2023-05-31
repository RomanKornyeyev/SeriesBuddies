<?php
    // ***** INIT *****
    require_once("../src/init.php");

    // ***** API TMDB *****
    use clases\api_tmdb\TMDB;

    // ***** FORM *****
    use clases\form\campo\Atipo;
    use clases\form\campo\Fecha;
    use clases\form\campo\Multiple;
    use clases\form\campo\Numero;
    use clases\form\campo\Texto;
    use clases\form\campo\File;
    use clases\form\claseMain\Formulario;

    $tmdb = new TMDB();
    
    $idSerie = $_GET['id'];
    //Recogemos la informacion de la serie por su ID
    $response = $tmdb->getSerieID($idSerie);
    
    //Si no está el genero es que proviene de buscar una serie
    (isset($_GET['id_genero'])) ? $idGenero = $_GET['id_genero'] : $idGenero = "";
    
    //Si se ha buscado una serie, se coge el primer genero al que pertecene esa serie para buscar el nombre y mostrarlo en el nav aux
    if ($idGenero == 0) {
        $idGenero = $response['serieGenres'][0]['id'];
    }
    $nbGenero = $tmdb->getGeneroPrincipalOptimizado($idGenero)[$idGenero];

    //Guardamos la pagina actual de donde nos encontramos
    (isset($_GET['pagina'])) ? $paginaActual = $_GET['pagina'] : $paginaActual = 1;

    //Comentarios por pagina a mostrar
    $registrosPagina = DWESBaseDatos::REGISTROS_POR_PAGINA;

    //Registro/comentario desde el que empezar a recorrer la tabla
    $registroInicial = ($paginaActual-1)*$registrosPagina;

    //Nombre, comentario, fecha e imagen del usuario + id de la serie en determinado rango
    $comentarios = $db->obtenRespuestasSerie($db, $idSerie, $registroInicial);

    //Obtenemos las medallas de ese usuario y lo guardamos en el mismo array comentarios
    foreach ($comentarios as $key => $comentario) {
        $comentarios[$key]['chips'] = $db->obtenBuddiesChipsRespuestas ($db, $comentario['id_user']);
    }

    //Total de los comentarios que hay de esa serie
    $totalRegistros = $db->obtenTotalRespuestas($db, $idSerie);

    //Total de paginas que hay que mostrar
    $totalPaginas   = ceil($totalRegistros / $registrosPagina);

    if (isset($_GET['pagina']) && $_GET['pagina'] > $totalPaginas) {
        $paginaActual = 1;

        $registroInicial = ($paginaActual-1)*$registrosPagina;
        $comentarios = $db->obtenRespuestasSerie($db, $idSerie, $registroInicial);
        $totalRegistros = $db->obtenTotalRespuestas($db, $idSerie);
        $totalPaginas   = ceil($totalRegistros / $registrosPagina);
    }

    //Devuelve la primera y la ultima pagina disponible
    //$limites = $db->obtenLimitesPaginacion($paginaActual, $totalPaginas);

    //Fotos de los primeros 5 usuarios que más han comentado esta serie
    $listadoBuddies = $db->obtenPrimerosBuddiesSerie($db, $idSerie);


    //Pintar formulario para publicar una respuesta
    //Si la sesion esta iniciada le va a redirigir a answers y si no a index
    if (isset($_GET['action'])) {
        if (!$sesionIniciada) {
            header('Location: login.php?error=Inicia sesión para poder publicar comentarios');
            die();
        } else {
            //obtenemos la URL actual
            // $urlActual = DWESBaseDatos::obtenUrlActual();

            // //quitamos el action a la URL (para posteo/edición de comentarios)
            // $nuevaUrl = DWESBaseDatos::eliminarParametroUrl($urlActual, "action");

            //echo $nuevaUrl;


            $accion = "";

            $valorCampo = "";
            $labelCampo = "";
            
            //Si el usuario responde a una serie
            if ($_GET['action'] == 'publicando') {
                $accion = "publicando";
                
                $labelCampo = 'Publica tu respuesta';
                // ========================================= FORM DE LOGIN =========================================
                //                             ACTION            METHOD           clases-css-form   ¿Vaciar al validar?   atr-extra(para forms con img)   CAMPOS
                $formulario = new Formulario("", Formulario::METHOD_POST, ["form"],        Formulario::VACIAR_NO,          "",                   array(
                    //                       ====================================== COMÚN =======================================================================  //  ======================== ESPECÍFICO ========================
                    //                  ¿Puede estar vacío? valor  name    label         clases-css-label         clases-css-wrapper       clases-css-input            tipoCampo    placeholder    regex
                    $mensaje =  new Texto (Atipo::NULL_NO, $valorCampo,  "mensaje",    $labelCampo,  ["label","label--text"],    ["input-wrapper"],  ["input","shadow-lightgray"],  Texto::TYPE_TAREA, " ",  Texto::DEFAULT_PATTERN_500),
                // === SUBMIT ===
                // claseWrappSubmit                           idSubmit  nameSubm  txtSubmit  clseSubmit
                ), ["input-wrapper","input-wrapper--submit"], "enviar", "enviar", "Publicar", ["btn", "btn--primary", "shadow-lightgray"]);

                if ($formulario->validarGlobal()) {
                    //Insert del comentario
                    DWESBaseDatos::insertarRespuesta($db, $idSerie, $_SESSION['id'], $mensaje->getValor());
                    
                    //redirección
                    header('Location: feed.php?id='.$idSerie.'&id_genero='.$idGenero.'&pagina='.$paginaActual);
                    die();
                }
    

                //Si el usuario edita una respuesta suya en concreto
            } else if ($_GET['action'] == 'editando') {
                $accion = "editando";

                $infoRespuesta = DWESBaseDatos::obtenInfoRespuesta($db, $_GET['id_respuesta']);
                
                if ($_SESSION['id'] == $infoRespuesta['id'] || $esAdmin) {
                    $labelCampo = 'Edita tu respuesta';
                    $valorCampo = $infoRespuesta['contenido'];

                    // ========================================= FORM DE LOGIN =========================================
                    //                             ACTION            METHOD           clases-css-form   ¿Vaciar al validar?   atr-extra(para forms con img)   CAMPOS
                    $formulario = new Formulario("", Formulario::METHOD_POST, ["form"],        Formulario::VACIAR_NO,          "",                   array(
                        //                       ====================================== COMÚN =======================================================================  //  ======================== ESPECÍFICO ========================
                        //                  ¿Puede estar vacío? valor  name    label         clases-css-label         clases-css-wrapper       clases-css-input            tipoCampo    placeholder    regex
                        $mensaje =  new Texto (Atipo::NULL_NO, $valorCampo,  "mensaje",    $labelCampo,  ["label","label--text"],    ["input-wrapper"],  ["input","shadow-lightgray"],  Texto::TYPE_TAREA, " ",  Texto::DEFAULT_PATTERN_500),
                    // === SUBMIT ===
                    // claseWrappSubmit                           idSubmit  nameSubm  txtSubmit  clseSubmit
                    ), ["input-wrapper","input-wrapper--submit"], "enviar", "enviar", "Editar", ["btn", "btn--primary", "shadow-lightgray"]);

                    
                    if ($formulario->validarGlobal()) {
                        //Update del comentario
                        DWESBaseDatos::actualizarRespuesta($db, $_GET['id_respuesta'], $mensaje->getValor());
                        
                        //redirección
                        header('Location: feed.php?id='.$idSerie.'&id_genero='.$idGenero.'&pagina='.$paginaActual);
                        die(); 
                    }
                }
            }
        }
    }

    $idRespuesta = "";
    if (isset($_GET['id_respuesta'])) {
        $linkIdRespuesta = "&id_respuesta=".$_GET['id_respuesta'];
        $idRespuesta = $_GET['id_respuesta'];
    }

    //paginación
    $paginaBase = "feed";
    $argumentos = array(
        "id" => $idSerie,
        "id_genero" => $idGenero,
        "action" => $accion,
        "id_respuesta" => $idRespuesta
    );
    $paginacion = DWESBaseDatos::obtenPaginacion($paginaBase, $paginaActual, $totalPaginas, $argumentos);


    // ********* INFO PARA EL TEMPLATE **********
    $tituloHead = $response['serieTitle']." - SeriesBuddies";
    $estiloEspecifico = "./css/feed.css";
    $scriptEspecifico = "./js/feed.js";
    $scriptLoadMode = "defer";
    $content;

    // ********* COMIENZO BUFFER **********
    ob_start();
?>

    <?php // ***** AUX NAV ***** ?>
    <nav class="nav-aux" aria-label="Main">
        <a href="./genders.php" class="primary-font primary-color">Géneros</a>
        <span class="primary-font color-white">&gt;</span>
        <a href="./series.php?id=<?=$idGenero?>" class="primary-font primary-color"><?=$nbGenero?></a>
        <span class="primary-font color-white">&gt;</span>
        <a href="./feed.php?id=<?=$idSerie?>&id_genero=<?=$idGenero?>" class="primary-font primary-color"><?=$response['serieTitle']?></a>
    </nav>

    <?php // ***** SERIE ***** ?>
    <div class="card">
        <div class="card__serie-img">
            <img class="img-fit" src="<?=$response['backdrop']?>" alt="serie-img">
            <h2 class="title title--serie"><?=$response['serieTitle']?></h2>
        </div>
        <div class="card__serie-info">
            <div class="info info--serie">
                <p class="text text--serie"><?=$response['seriePlot']?></p>
                <?php foreach ($response['serieGenres'] as $genero) { ?>
                    <a href="./series.php?id=<?=$genero['id']?>" class="btn btn--outline_filter">#<?=$genero['name']?></a>
                <?php } ?>
            </div>
            <div class="links__buddy">
                <div class="icon icon--super-buddies">
                    <?php foreach ($listadoBuddies as $buddie) { ?>
                        <a href="./profile.php?id=<?=$buddie['id']?>" class="icon__buddy img-user-post"><img class="img-fit" src="<?=$buddie['img']?>" alt="serie-img"></a>
                    <?php } ?>
                </div>
                <div class="width-100 info--super-buddies">
                    <a class="info info--buddy" href="./buddies.php?id-serie=<?=$idSerie?>">Ver todos los buddies &gt;</a>
                </div>
            </div> 
        </div>
    </div>
    <?php if ($_GET['action']=='publicando' || ($_GET['action'] == 'editando' && ($_SESSION['id'] == $infoRespuesta['id'] || $esAdmin))) { ?>
        <div id="respuesta" class="width-100">
            <?php $formulario->pintarGlobal(); ?>
        </div>
    <?php } ?>
    <?php
        // ***** PAGINACION + BTN RESPONDER *****
        //Si el usuario no está publicando ni respondiendo, el boton es "RESPONDER", si el usuario está publicando/editando, el botón es "VOLVER"
        //lo que devuelve al user a la misma página, pero sin acción de editar/responder

        //si el user no está respondiendo/editando, la paginación lleva a "x" página SIN ACCIÓN
        //si el user está respondiendo/editando, la paginación lleva a la página CON ACCIÓN
        
    ?>
    <div class="pagination pagination--plus-response">
        <div class="response-btn">
            <?php if(isset($_GET['action'])){ ?>
                <a href="./feed.php?id=<?=$idSerie?>&id_genero=<?=$idGenero?>&pagina=<?=$paginaActual?>" class="btn btn--secondary"><i class="fa-solid fa-arrow-left"></i>&nbsp;Volver</a>
            <?php }else{ ?>
                <a href="./feed.php?id=<?=$idSerie?>&id_genero=<?=$idGenero?>&action=publicando" class="btn">Responder &nbsp;<i class="fa-solid fa-pen-to-square"></i></a>
            <?php }?>
        </div>
        <?php if(!empty($comentarios)) { ?>
            <?=$paginacion?>
        <?php } ?>
    </div>

    <?php // ***** RESPUESTAS ***** ?>
    <div id="respuestas" class="respuestas">
        <?php foreach ($comentarios as $comentario) { ?>
            <div class="card">
                <div class="card__post">
                    <div class="card__post-img">
                        <div class="img-user-post">
                            <img class="img-fit" src="<?=$comentario['img']?>" alt="serie-img">
                        </div>
                        <h2 class="title title--user"><?=$comentario['nombre']?></h2>
                        <div class="icons">
                            <?php foreach ($comentario['chips'] as $value) { ?>
                                <div class="img-fit">
                                    <img class="img-fit" src="<?=$value['img']?>" alt="chip-img">
                                </div>
                            <?php } ?>
                            <!-- <div class="icons__chip"></div>
                            <div class="icons__chip"></div> -->
                        </div>
                    </div>
                    <div class="card__post-comment">
                        <div class="info info--comment">
                            <div class="date-post">Publicado el <?=$comentario['fecha_formateada']?></div>
                            <div class="admin-area">
                                <?php //botones de editar/eliminar, solo cuando id=id o es admin ?>
                                <?php if($comentario['id_user'] == $_SESSION['id'] || $esAdmin) { ?>
                                    <a href="./feed.php?id=<?=$idSerie?>&action=editando&id_respuesta=<?=$comentario['id_respuesta']?>&id_genero=<?=$idGenero?>&pagina=<?=$paginaActual?>" class="btn btn--secondary btn--sm-responsive btn--bold">Editar</a>
                                    <button class="btn btn--error btn--sm-responsive btn--bold" onclick="eliminar(this, <?=$comentario['id_respuesta']?>, <?=$idSerie?>, <?=$idGenero?>, <?=$paginaActual?>, <?=$totalPaginas?>)">Eliminar</button>
                                <?php } ?>
                            </div>
                        </div>
                        <p class="text text--comment"><?=$comentario['contenido']?></p>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
    
    <?php if(!empty($comentarios)) { ?>
        <div class="pagination pagination--plus-response">
            <div class="response-btn">
                <?php if(isset($_GET['action'])){ ?>
                    <a href="./feed.php?id=<?=$idSerie?>&id_genero=<?=$idGenero?>&pagina=<?=$paginaActual?>" class="btn btn--secondary"><i class="fa-solid fa-arrow-left"></i>&nbsp;Volver</a>
                <?php }else{ ?>
                    <a href="./feed.php?id=<?=$idSerie?>&id_genero=<?=$idGenero?>&action=publicando" class="btn">Responder &nbsp;<i class="fa-solid fa-pen-to-square"></i></a>
                <?php }?>
            </div>
            <?=$paginacion?>
        </div>
    <?php } ?>

    
    <nav class="nav-aux nav-aux--bottom" aria-label="Volver">
        <a href="./series.php?id=<?=$idGenero?>" class="primary-font primary-color"><i class="fa-solid fa-arrow-left"></i> Volver a <?=$nbGenero?></a>
    </nav>

<?php

// ********* FIN BUFFER + LLAMADA AL TEMPLATE **********
$content = ob_get_contents();
ob_end_clean();
require("template.php");

?>