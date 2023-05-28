<?php

    // ***** INIT *****
    require_once("../src/init.php");

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
    // echo '<pre>';
    // print_r($infoBuddie);
    // echo '</pre>';

    // ********* INFO PARA EL TEMPLATE **********
    $tituloHead = $infoBuddie['tarjeta']['nombre']." - SeriesBuddies";
    $estiloEspecifico = "./css/profile.css";
    $scriptEspecifico = "./js/galeria.js";
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
                            <h1 class="title title--user"><?=$infoBuddie['tarjeta']['nombre']?></h1>
                            <p class="info info--user"><?=$infoBuddie['tarjeta']['alias']?></p>
                            <p class="info info--user">Se unió el <?=$infoBuddie['tarjeta']['fecha']?></p>
                            <p class="text text--user"><?=$infoBuddie['tarjeta']['descripcion']?></p>
                        </div>
                        
                        <div class="button-card">
                            <a class="btn btn--card" href="#">Conectar</a>
                        </div>
                    </div>
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
                                        <a href="./feed.php?id=<?=$series[$key]['id_serie']?>">
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
                
            </main>

<?php
    // ********* FIN BUFFER + LLAMADA AL TEMPLATE **********
    $content = ob_get_contents();
    ob_end_clean();
    require("template.php");
?>