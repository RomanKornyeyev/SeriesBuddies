<?php

    // ********* INIT **********
    require_once("../src/init.php");

    // ********* PETICIONES **********
    use clases\peticiones\Peticion;
    $peticionFooter = new Peticion($esAdmin);

    // si hay un get, significa que están filtrados por serie
    if (isset($_GET['id-serie'])) {
        $idSerie = $_GET['id-serie'];
        //obtenemos todos los users filtrados
        $consulta = $db->getListadoBuddies($db, $idSerie);
    }else{
        //obtenemos todos los users
        $consulta = DWESBaseDatos::obtenUsuarios($db);
    }
    

    // ********* INFO PARA EL TEMPLATE **********
    $tituloHead = "Buddies - SeriesBuddies";
    $estiloEspecifico = "./css/buddies.css";
    $scriptEspecifico = "./js/buddies.js";
    $scriptLoadMode = "defer";
    $content;

    // ********* COMIENZO BUFFER **********
    ob_start();
?>

    <h1 class="title title--l text-align-center">BUDDIES</h1>
    <div class="main__content">


    <?php // ***** GENERACIÓN DE USERS ***** ?>
    <?php //por cada user pintamos una tarjeta ?>
    <?php foreach ($consulta as $value){?>
            <?php if($value['id'] != $_SESSION['id']){ ?>
            <!-- CARD (GLOBAL) -->
            <div class="card card--buddy">

                <!-- CARD BODY -->
                <div class="buddy__body">
                    <div class="profile-img">
                        <img class="img-fit" src="<?=$value['img']?>" alt="profile-img">
                    </div>
                    <div class="profile-info">
                        <h2 class="profile-name"><?=$value['nombre']?></h2>
                        <p class="profile-hashtag">@<?=$value['nombre']?></p>
                        <div class="profile-achievements">
                            <div class="achievements">
                                <p>xx Buddies</p>
                                <p>xxxx Posts</p>
                            </div>
                            <div class="barrita"></div>
                            <div class="achievements">
                                <p>xx Series</p>
                                <p>xx Chips</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CARD FOOTER -->
                <div class="buddy__footer-external-layer">
                    <?php
                        //si el user no tiene sesión iniciada
                        if (!$sesionIniciada) {
                            $peticionFooter->pintaSesionNoIniciada($value['id']);

                        //si el user SI TIENE la sesión iniciada
                        }else{

                            //obtenemos info sobre el estado de petición de amistad
                            $peticion = DWESBaseDatos::obtenPeticion($db, $_SESSION['id'], $value['id']);

                            //si ninguno ha mandado petición de amistad
                            if ($peticion == "" || $peticion == null) {
                                $peticionFooter->pintaAmistadNula($value['id']);
                            
                            //si el user actual (SESIÓN) ha ENVIADO peti al user seleccioando
                            }else if($peticion['estado'] == DWESBaseDatos::PENDIENTE && $peticion['id_emisor'] == $_SESSION['id']) {
                                $peticionFooter->pintaAmistadEnviada($value['id']);

                            //si el user actual (SESIÓN) ha RECIBIDO peti del user seleccioando    
                            }else if($peticion['estado'] == DWESBaseDatos::PENDIENTE && $peticion['id_receptor'] == $_SESSION['id']) {
                                $peticionFooter->pintaAmistadRecibida($value['id']);

                            //si son AMOGUS  
                            }else if($peticion['estado'] == DWESBaseDatos::ACEPTADA) {
                                $peticionFooter->pintaAmistadMutua($value['id']);
                            }
                        }
                    ?>
                </div>
            </div>
        <?php } ?>
    <?php } ?>

    </div>

<?php

    // ********* FIN BUFFER + LLAMADA AL TEMPLATE **********
    $content = ob_get_contents();
    ob_end_clean();
    require("template.php");
    
?>