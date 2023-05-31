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
    
    //Devuelve las imagenes de todas las series (*** ES LO QUE LAGUEA ***)
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
    <div class="primary-profile-info">               
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
                            <a href="./profile.php?id=<?=$idUsuario?>&action=editando" class="btn btn--secondary btn--bold"><i class="fa-solid fa-user-gear"></i></a>
                            <!-- <button class="btn btn--error btn--sm-responsive btn--bold" onclick="eliminar()">Eliminar</button> -->
                        <?php } ?>
                    </div>
                    <p class="info info--user"><?=$infoBuddie['tarjeta']['alias']?></p>
                    <p class="info info--user">Se unió el <?=$infoBuddie['tarjeta']['fecha']?></p>
                    <p class="text text--user"><?=$infoBuddie['tarjeta']['descripcion']?></p>
                </div>
                
                
                <!-- CARD FOOTER -->
                <div class="buddy__footer-external-layer">
                    <?php
                        //si el user no tiene sesión iniciada
                        if (!$sesionIniciada) {
                            echo $peticionFooter->pintaSesionNoIniciada($infoBuddie['tarjeta']['id']);

                        //si el user SI TIENE la sesión iniciada
                        }else{
                            if($_SESSION['id'] == $infoBuddie['tarjeta']['id']){
                                echo $peticionFooter->pintaSesionNoIniciada($infoBuddie['tarjeta']['id']);
                            }else{
                                //obtenemos info sobre el estado de petición de amistad
                                $peticion = DWESBaseDatos::obtenPeticion($db, $_SESSION['id'], $infoBuddie['tarjeta']['id']);

                                //si ninguno ha mandado petición de amistad
                                if ($peticion == "" || $peticion == null) {
                                    echo $peticionFooter->pintaAmistadNula($infoBuddie['tarjeta']['id'], 1, 1, Peticion::FOOTER_PROFILE);
                                    
                                //si el user actual (SESIÓN) ha ENVIADO peti al user seleccioando
                                }else if($peticion['estado'] == DWESBaseDatos::PENDIENTE && $peticion['id_emisor'] == $_SESSION['id']) {
                                    echo $peticionFooter->pintaAmistadEnviada($infoBuddie['tarjeta']['id'], 1, 1, Peticion::FOOTER_PROFILE);

                                //si el user actual (SESIÓN) ha RECIBIDO peti del user seleccioando    
                                }else if($peticion['estado'] == DWESBaseDatos::PENDIENTE && $peticion['id_receptor'] == $_SESSION['id']) {
                                    echo $peticionFooter->pintaAmistadRecibida($infoBuddie['tarjeta']['id'], 1, 1, Peticion::FOOTER_PROFILE);

                                //si son AMOGUS  
                                }else if($peticion['estado'] == DWESBaseDatos::ACEPTADA) {
                                    echo $peticionFooter->pintaAmistadMutua($infoBuddie['tarjeta']['id'], 1, 1, Peticion::FOOTER_PROFILE);
                                }
                            }
                        }
                    ?>
                </div>
            </div>
        </div>

        <!-- peticiones -->
        <div class="card card--petitions">
            <h3 class="petitions-title">Peticiones de amistad</h3>
            <div class="petitions__content">


                <div class="petition-card">

                    <div class="petition-card__info">
                        <div class="petition-card-img-wrapper">
                            <!-- <div class="petition-card-img"> -->
                                <img class="img-fit" src="./upload/perfiles/default.png" alt="">
                            <!-- </div> -->
                        </div>
                        <h3 class="petition-card-name">Román</h3>
                    </div>


                    <div class="buddy__footer-external-layer">
                        <div class='buddy__footer-internal-layer' id='6'>
                            <div class='buddy__footer buddy__footer--primary grid-col-3'>
                                <button class='btn btn--card' onclick='subir(this)'>
                                    <i class='fa-solid fa-id-card'></i>&nbsp;
                                    <span class='primary-font'>Volver</span>
                                    &nbsp;<i class='fa-solid fa-arrow-down'></i>
                                </button>
                            </div>
                            <div class='buddy__footer buddy__footer--primary grid-col-2'>
                                <button class='btn btn--card btn--success-card' onclick='peticion(this, 6, `aceptar`, 1, 1, 2)'>
                                    <span class='primary-font'>Aceptar&nbsp;</span>
                                    <i class='fa-solid fa-check'></i>
                                </button>
                                <button class='btn btn--card btn--error-card' onclick='peticion(this, 6, `rechazar`, 1, 1, 2)'>
                                    <span class='primary-font'>Rechazar&nbsp;</span>
                                    <i class='fa-solid fa-xmark'></i>
                                </button>
                            </div>
                        </div>
                    </div>

                </div>



                <div class="petition-card">

                    <div class="petition-card__info">
                        <div class="petition-card-img-wrapper">
                            <!-- <div class="petition-card-img"> -->
                                <img class="img-fit" src="./upload/perfiles/default.png" alt="">
                            <!-- </div> -->
                        </div>
                        <h3 class="petition-card-name">Román</h3>
                    </div>


                    <div class="buddy__footer-external-layer">
                        <div class='buddy__footer-internal-layer' id='6'>
                            <div class='buddy__footer buddy__footer--primary grid-col-3'>
                                <button class='btn btn--card' onclick='subir(this)'>
                                    <i class='fa-solid fa-id-card'></i>&nbsp;
                                    <span class='primary-font'>Volver</span>
                                    &nbsp;<i class='fa-solid fa-arrow-down'></i>
                                </button>
                            </div>
                            <div class='buddy__footer buddy__footer--primary grid-col-2'>
                                <button class='btn btn--card btn--success-card' onclick='peticion(this, 6, `aceptar`, 1, 1, 2)'>
                                    <span class='primary-font'>Aceptar&nbsp;</span>
                                    <i class='fa-solid fa-check'></i>
                                </button>
                                <button class='btn btn--card btn--error-card' onclick='peticion(this, 6, `rechazar`, 1, 1, 2)'>
                                    <span class='primary-font'>Rechazar&nbsp;</span>
                                    <i class='fa-solid fa-xmark'></i>
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="petition-card">

                    <div class="petition-card__info">
                        <div class="petition-card-img-wrapper">
                            <!-- <div class="petition-card-img"> -->
                                <img class="img-fit" src="./upload/perfiles/default.png" alt="">
                            <!-- </div> -->
                        </div>
                        <h3 class="petition-card-name">Román</h3>
                    </div>


                    <div class="buddy__footer-external-layer">
                        <div class='buddy__footer-internal-layer' id='6'>
                            <div class='buddy__footer buddy__footer--primary grid-col-3'>
                                <button class='btn btn--card' onclick='subir(this)'>
                                    <i class='fa-solid fa-id-card'></i>&nbsp;
                                    <span class='primary-font'>Volver</span>
                                    &nbsp;<i class='fa-solid fa-arrow-down'></i>
                                </button>
                            </div>
                            <div class='buddy__footer buddy__footer--primary grid-col-2'>
                                <button class='btn btn--card btn--success-card' onclick='peticion(this, 6, `aceptar`, 1, 1, 2)'>
                                    <span class='primary-font'>Aceptar&nbsp;</span>
                                    <i class='fa-solid fa-check'></i>
                                </button>
                                <button class='btn btn--card btn--error-card' onclick='peticion(this, 6, `rechazar`, 1, 1, 2)'>
                                    <span class='primary-font'>Rechazar&nbsp;</span>
                                    <i class='fa-solid fa-xmark'></i>
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="petition-card">

                    <div class="petition-card__info">
                        <div class="petition-card-img-wrapper">
                            <!-- <div class="petition-card-img"> -->
                                <img class="img-fit" src="./upload/perfiles/default.png" alt="">
                            <!-- </div> -->
                        </div>
                        <h3 class="petition-card-name">Román</h3>
                    </div>


                    <div class="buddy__footer-external-layer">
                        <div class='buddy__footer-internal-layer' id='6'>
                            <div class='buddy__footer buddy__footer--primary grid-col-3'>
                                <button class='btn btn--card' onclick='subir(this)'>
                                    <i class='fa-solid fa-id-card'></i>&nbsp;
                                    <span class='primary-font'>Volver</span>
                                    &nbsp;<i class='fa-solid fa-arrow-down'></i>
                                </button>
                            </div>
                            <div class='buddy__footer buddy__footer--primary grid-col-2'>
                                <button class='btn btn--card btn--success-card' onclick='peticion(this, 6, `aceptar`, 1, 1, 2)'>
                                    <span class='primary-font'>Aceptar&nbsp;</span>
                                    <i class='fa-solid fa-check'></i>
                                </button>
                                <button class='btn btn--card btn--error-card' onclick='peticion(this, 6, `rechazar`, 1, 1, 2)'>
                                    <span class='primary-font'>Rechazar&nbsp;</span>
                                    <i class='fa-solid fa-xmark'></i>
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
                



            </div>
        </div>
    </div> 

    <!-- <div class="petition">
        <?php //foreach ($peticiones as $key => $peticion) { ?>
            <div class="card__user-img">
                <div class="profile-img">
                    <img class="img-fit" src="<?php // echo $peticion['img']?>" alt="profile-img">
                </div>
            </div>
            <div class="card__user-body">
                <div class="card__user-info">
                    <h1 class="title title--user title--petition"><?php // echo$peticion['nombre']?></h1>
                    <p class="info info--user info--petition">Petición de amistad <?php // echo$peticion['estado']?></p>
                </div>
            </div>
        <?php //} ?>
    </div> -->
    
    <div class="carousel">
        <h2 class="title title--carousel"><span class="title--first-lane">MIS</span> SERIES</h2>

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
                    <div class="carrusel">
                        <div>
                            <a href="./feed.php?id=<?=$series[$key]['id_serie']?>&id_genero=0">
                                <picture>
                                    <img src="<?=$infoBuddie['series'][$key]?>" alt="imagen" class="img-fit">
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
        <h2 class="title title--carousel"><span class="title--first-lane">MIS</span> BUDDIES</h2>

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
                    <div class="carrusel">
                        <div>
                            <a href="./profile.php?id=<?=$value['id_receptor']?>">
                                <picture>
                                    <img src="<?=$value['img']?>" alt="imagen" class="img-fit">
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
            <h2 class="title title--carousel"><span class="title--first-lane">MIS</span> CHIPS</h2>

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
                                        <img src="<?=$value['img']?>" alt="<?=$value['nombre']?>" class="img-fit">
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

<?php
    // ********* FIN BUFFER + LLAMADA AL TEMPLATE **********
    $content = ob_get_contents();
    ob_end_clean();
    require("template.php");
?>