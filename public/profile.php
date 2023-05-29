<?php

    // ***** INIT *****
    require_once("../src/init.php");

    // ***** PETICIONES *****
    use clases\peticiones\Peticion;
    $peticionFooter = new Peticion($esAdmin);

    // ***** API TMDB *****
    use clases\api_tmdb\TMDB;
    $tmdb = new TMDB();

    $idUsuario = $_GET['id'];

    //Saco la informacion del buddie de la tabla usuarios.
    $infoBuddie['tarjeta'] = DWESBaseDatos::obtenInfoBuddieTarjeta($db, $idUsuario);

    //Devuelve los id de las series en las que ha comentado ese buddie
    $series = DWESBaseDatos::obtenInfoBuddieIdSeries($db, $idUsuario);
    
    //Devuelve las imagenes de todas las series
    $infoBuddie['series'] = $tmdb->getSeriesPosters($series);

    //Devuelve los amigos (id_receptor, nombre y la img) de ese buddie
    $infoBuddie['buddies'] = DWESBaseDatos::obtenInfoBuddieBuddies($db, $idUsuario);

    //Devuelve los chips (id, img, nombre) de ese buddie
    $infoBuddie['chips'] = DWESBaseDatos::obtenInfoBuddieChips($db, $idUsuario);

    //Obtiene el nombre, el id_emisor, id_receptor y el estado de las peticiones pendientes de ese buddie
    $peticiones = DWESBaseDatos::obtenPeticionesPendientes($db, $idUsuario);

    // echo '<pre class="color-white">';
    // print_r($infoBuddie);
    // echo '</pre>';

    // echo '<pre class="color-white">';
    // print_r($peticiones);
    // echo '</pre>';


    // ********* INFO PARA EL TEMPLATE **********
    $tituloHead = $infoBuddie['tarjeta']['nombre']." - SeriesBuddies";
    $estiloEspecifico = "./css/profile.css";
    $scriptEspecifico = "./js/buddies.js";
    $scriptLoadMode = "defer";
    $content;
    
    // ********* COMIENZO BUFFER **********
    ob_start();
?>
            <main class="main">
                
                    <div class="card">
                        <div class="card__user-img">
                            <div class="profile-img">
                                <img class="img-fit" src="<?=$infoBuddie['tarjeta']['img']?>" alt="profile-img">
                            </div>
                        </div>
                        <div class="card__user-bio">
                            <div class="card__user-info">
                                <div class="admin-area">
                                    <h1 class="title title--user"><?=$infoBuddie['tarjeta']['nombre']?></h1>
                                    <?php //botones de editar/eliminar, solo cuando id=id o es admin ?>
                                    <?php if($idUsuario == $_SESSION['id'] || $esAdmin) { ?>
                                        <a href="./edit.php?id=<?=$idUsuario?>&action=editando" class="btn btn--secondary btn--sm-responsive btn--bold">Editar</a>
                                        <!-- <button class="btn btn--error btn--sm-responsive btn--bold" onclick="eliminar()">Eliminar</button> -->
                                    <?php } ?>
                                </div>
                                <p class="info info--user"><?=$infoBuddie['tarjeta']['alias']?></p>
                                <p class="info info--user">Se unió el <?=$infoBuddie['tarjeta']['fecha']?></p>
                                <p class="text text--user"><?=$infoBuddie['tarjeta']['descripcion']?></p>
                            </div>
                            
                            
                            <div class="button-card">
                                <a class="btn btn--card" href="#">Conectar</a>
                            </div>
                        </div>
                    </div>

                    <div class="petition">
                        <?php foreach ($peticiones as $key => $peticion) { ?>
                            <div class="card__user-img">
                                <div class="profile-img">
                                    <img class="img-fit" src="<?=$peticion['img']?>" alt="profile-img">
                                </div>
                            </div>
                            <div class="card__user-body">
                                <div class="card__user-info">
                                    <h1 class="title title--user title--petition"><?=$peticion['nombre']?></h1>
                                    <p class="info info--user info--petition">Petición de amistad <?=$peticion['estado']?></p>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                
                <div class="carousel">
                    <h2 class="title title--carousel">MIS SERIES</h2>

                    <!-- Galeria en sí: botones y fotos -->
                    <div class="list" id="list">

                        <!-- Boton -->
                        <button class="carrusel-arrow carrusel-prev" id="button-prev" data-button="button-prev"
                            onclick="app.processingButton(event)">
                            <i class="fa-solid fa-angle-left"></i>
                        </button>
                        
                        <!-- Todas las imagenes -->
                        <div class="gallery" id="gallery">
                            <?php foreach ($infoBuddie['series'] as $key => $value) { ?>
                                <div class="carrusel img">
                                    <div>
                                        <a href="./feed.php?id=<?=$series[$key]['id_serie']?>&id_genero=0">
                                            <picture>
                                                <img src="<?=$infoBuddie['series'][$key]?>" alt="imagen">
                                            </picture>
                                        </a>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>

                        <!-- Boton -->
                        <button class="carrusel-arrow carrusel-next" id="button-next" data-button="button-next"
                            onclick="app.processingButton(event)">
                            <i class="fa-solid fa-angle-right"></i>
                        </button>

                    </div>
                </div>


                <div class="carousel">
                    <h2 class="title title--carousel">MIS BUDDIES</h2>

                    <!-- Galeria en sí: botones y fotos -->
                    <div class="list" id="list">

                        <!-- Boton -->
                        <button class="carrusel-arrow carrusel-prev" id="button-prev" data-button="button-prev"
                            onclick="app.processingButton(event)">
                            <i class="fa-solid fa-angle-left"></i>
                        </button>
                        
                        <!-- Todas las imagenes -->
                        <div class="gallery" id="gallery">
                            <?php foreach ($infoBuddie['buddies'] as $key => $value) { ?>
                                <div class="carrusel img">
                                    <div>
                                        <a href="./profile.php?id=<?=$value['id_receptor']?>">
                                            <picture>
                                                <img src="<?=$value['img']?>" alt="imagen">
                                            </picture>
                                        </a>
                                    </div>
                                </div>
                                <?php } ?>
                        </div>

                        <!-- Boton -->
                        <button class="carrusel-arrow carrusel-next" id="button-next" data-button="button-next"
                            onclick="app.processingButton(event)">
                            <i class="fa-solid fa-angle-right"></i>
                        </button>

                    </div>
                </div>

                <?php if (count($infoBuddie['chips']) != 0) { ?>
                    <div class="carousel">
                        <h2 class="title title--carousel">MIS CHIPS</h2>

                        <!-- Galeria en sí: botones y fotos -->
                        <div class="list" id="list">

                            <!-- Boton -->
                            <button class="carrusel-arrow carrusel-prev" id="button-prev" data-button="button-prev"
                                onclick="app.processingButton(event)">
                                <i class="fa-solid fa-angle-left"></i>
                            </button>
                            
                            <!-- Todas las imagenes -->
                            <div class="gallery" id="gallery">
                                <?php foreach ($infoBuddie['chips'] as $key => $value) { ?>
                                    <div class="carrusel img">
                                        <div>
                                            <a href="./profile.php?id=<?=$value['id_usuario']?>">
                                                <picture>
                                                    <img src="<?=$value['img']?>" alt="<?=$value['nombre']?>">
                                                </picture>
                                            </a>
                                        </div>
                                    </div>
                                    <?php } ?>
                            </div>

                            <!-- Boton -->
                            <button class="carrusel-arrow carrusel-next" id="button-next" data-button="button-next"
                                onclick="app.processingButton(event)">
                                <i class="fa-solid fa-angle-right"></i>
                            </button>

                        </div>
                    </div>
                <?php } ?>
            </main>

<?php
    // ********* FIN BUFFER + LLAMADA AL TEMPLATE **********
    $content = ob_get_contents();
    ob_end_clean();
    require("template.php");
?>