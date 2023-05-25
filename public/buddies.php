<?php

    // ***** INIT *****
    require_once("../src/init.php");

    // ***** PETICIONES *****
    use clases\peticiones\Peticion;
    $peticionFooter = new Peticion($esAdmin);

    if (isset($_GET['pagina'])) {
        $paginaActual = $_GET['pagina'];
    } else {
        $paginaActual = 1;
    }

    //Comentarios por pagina a mostrar
    $registrosPagina = DWESBaseDatos::REGISTROS_POR_PAGINA;

    //Registro/comentario desde el que empezar a recorrer la tabla
    $registroInicial = ($paginaActual-1)*$registrosPagina;

    // si hay un get, significa que están filtrados por serie
    if (isset($_GET['id-serie'])) {
        $idSerie = $_GET['id-serie'];
        //obtenemos todos los users filtrados por serie
        $consulta = DWESBaseDatos::obtenListadoBuddiesPorSerie($db, $idSerie, $registroInicial);
    }else{
        //obtenemos todos los users
        $consulta = DWESBaseDatos::obtenListadoBuddies($db, $registroInicial);
    }

    
    
    //total registros
    $totalRegistros = DWESBaseDatos::obtenTotalUsuarios($db)['total_usuarios'];
    
    //Total de paginas que hay que mostrar
    $totalPaginas = ceil($totalRegistros / $registrosPagina);

    // if (!$totalPaginas >= $paginaActual) {
    //     $paginaActual = 1;
    // }
    
    

    //Devuelve la primera y la ultima pagina disponible
    $limites = DWESBaseDatos::obtenLimitesPaginacion($paginaActual, $totalPaginas);
    

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
        
            <?php //if($value['id'] != $_SESSION['id']){ ?>
            <!-- CARD (GLOBAL) -->
            <div class="card card--buddy">

                <!-- CARD BODY -->
                <div class="buddy__body">
                    <div class="profile-img">
                        <img class="img-fit" src="<?=$value['img']?>" alt="profile-img">
                    </div>
                    <div class="profile-info">
                        <h2 class="profile-name"><?=$value['nombre']?></h2>
                        <p class="profile-hashtag"><?=$value['alias']?></p>
                        <div class="profile-achievements">
                            <div class="achievements">
                                <p><?=$value['total_amigos']?> Buddies</p>
                                <p><?=$value['total_respuestas']?> Posts</p>
                            </div>
                            <div class="barrita"></div>
                            <div class="achievements">
                                <p><?=$value['total_series']?> Series</p>
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
                            echo $peticionFooter->pintaSesionNoIniciada($value['id']);

                        //si el user SI TIENE la sesión iniciada
                        }else{

                            //obtenemos info sobre el estado de petición de amistad
                            $peticion = DWESBaseDatos::obtenPeticion($db, $_SESSION['id'], $value['id']);

                            //si ninguno ha mandado petición de amistad
                            if ($peticion == "" || $peticion == null) {
                                echo $peticionFooter->pintaAmistadNula($value['id'], $paginaActual, $totalPaginas);
                                 
                            //si el user actual (SESIÓN) ha ENVIADO peti al user seleccioando
                            }else if($peticion['estado'] == DWESBaseDatos::PENDIENTE && $peticion['id_emisor'] == $_SESSION['id']) {
                                echo $peticionFooter->pintaAmistadEnviada($value['id'], $paginaActual, $totalPaginas);

                            //si el user actual (SESIÓN) ha RECIBIDO peti del user seleccioando    
                            }else if($peticion['estado'] == DWESBaseDatos::PENDIENTE && $peticion['id_receptor'] == $_SESSION['id']) {
                                echo $peticionFooter->pintaAmistadRecibida($value['id'], $paginaActual, $totalPaginas);

                            //si son AMOGUS  
                            }else if($peticion['estado'] == DWESBaseDatos::ACEPTADA) {
                                echo $peticionFooter->pintaAmistadMutua($value['id'], $paginaActual, $totalPaginas);
                            }
                        }
                    ?>
                </div>
            </div>
        <?php //} ?>
    <?php } ?>

    </div>

    <div class="pagination">
        <?php if ($totalPaginas > 1) {
            //Te saca el boton de ir hacia atras si no estas en la primera pagina
            if ($paginaActual != 1) { ?>
                <a href="./buddies.php?pagina=<?=($paginaActual-1)?>" class="btn btn--primary btn--sm">&lt;</a>
            <?php }

            //Mostramos la primera pagina y los ...
            if ($limites['primera'] != 1) { ?>
                <a href="./buddies.php?pagina=1" class="btn btn--outline btn--sm">1</a>
                <span class="btn btn--outline btn--sm">...</span>
            <?php }

            //Te pinta el boton de la pagina en la que estas, las anteriores y las siguientes (intervalo de 5)
            for ($i=$limites['primera']; $i <= $limites['ultima']; $i++) { 
                if ($paginaActual == $i) { ?>
                    <a href="./buddies.php?pagina=<?=$paginaActual?>" class="btn btn--primary btn--sm"><?=$paginaActual?></a>
                <?php } else { ?>
                <a href="./buddies.php?pagina=<?=$i?>" class="btn btn--outline btn--sm"><?=$i?></a>
            <?php }
            }
            
            //Saca la ultima página que hay en el registro
            if ($limites['ultima'] != $totalPaginas) { ?>
                <span class="btn btn--outline btn--sm">...</span>
                <a href="./buddies.php?pagina=<?=$totalPaginas?>" class="btn btn--outline btn--sm"><?=$totalPaginas?></a>;
            <?php }

            //Te saca el boton de ir hacia adelante si no estas en la última pagina
            if ($paginaActual != $totalPaginas) { ?>
                <a href="./buddies.php?pagina=<?=($paginaActual+1)?>" class="btn btn--primary btn--sm">&gt;</a>
            <?php } ?>
            
        <?php } ?>
    </div>

<?php

    // ********* FIN BUFFER + LLAMADA AL TEMPLATE **********
    $content = ob_get_contents();
    ob_end_clean();
    require("template.php");
    
?>