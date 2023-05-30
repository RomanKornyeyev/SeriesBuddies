<?php

    // ***** INIT *****
    require_once("../src/init.php");
    
    // ***** FORMULARIOS *****
    use clases\form\campo\Atipo;
    use clases\form\campo\Texto;
    use clases\form\claseMain\Formulario;

    // ***** PETICIONES *****
    use clases\peticiones\Peticion;
    $peticionFooter = new Peticion($esAdmin);

    
    // ========================================= FORM DE BUSCAR SERIE =========================================
    //                             ACTION            METHOD           clases-css-form   ¿Vaciar al validar?   atr-extra(para forms con img)   CAMPOS
    $formBuddies = new Formulario("buddies.php", Formulario::METHOD_GET, ["h-form"],        Formulario::VACIAR_NO,          "",                   array(
        //                       ====================================== COMÚN =======================================================================  //  ======================== ESPECÍFICO ========================
        //                  ¿Puede estar vacío? valor         name   label  clases-css-label  clases-css-wrapper  clases-css-input             tipoCampo       placeholder      regex
        $buddies = new Texto (Atipo::NULL_NO, null,"buddies", "",   ["d-none"],    ["input-wrapper"],  ["h-input"],              Texto::TYPE_TEXT, "Busca a un buddy...",  Texto::DEFAULT_PATTERN_25),
    // === SUBMIT ===
    // claseWrappSubmit      idSubmit  nameSubm  txtSubmit  clseSubmit
    ), ["h-submit-wrapper"], "buscar-buddies", "buscar-buddies", "<i class='fa-solid fa-magnifying-glass'></i>", ["btn", "btn--primary", "h-submit-button"]);

    if ($formBuddies->validarGlobal()) {
        // $opcionExtra = (isset($_GET['id-serie']))? 'id-serie='.$_GET['id-serie'].'&' : "";

        // //redirección
        // header('Location: ./buddies.php?'.$opcionExtra.'busqueda='.$buddies->getValor());
        // die();
    }

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

    if (isset($_GET['busqueda'])) {
        $busqueda = $_GET['busqueda'];
        
        //Devuelvo todos los usuarios que coincidan con esa busqueda
        $consulta = DWESBaseDatos::obtenListadoBuddiesBusqueda($db, $busqueda, $registroInicial);

        //Total de paginas que hay que mostrar
        $totalPaginas = ceil($totalRegistros / $registrosPagina);

        //paginación
        $argumentos = array(
            "busqueda" => $idSerie
        );
        

    } else {
        //total registros
        $totalRegistros = DWESBaseDatos::obtenTotalUsuarios($db)['total_usuarios'];
        
        //paginación
        $argumentos = array(
            "id-serie" => $idSerie
        );
    }
    
    //Total de paginas que hay que mostrar
    $totalPaginas = ceil($totalRegistros / $registrosPagina);
    
    if ($paginaActual > $totalPaginas) {
        $paginaActual = 1;
    }

    $paginaBase = "buddies";

    $paginacion = DWESBaseDatos::obtenPaginacion($paginaBase, $paginaActual, $totalPaginas, $argumentos);

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
    <!-- search form -->
    <?php $formBuddies->pintarGlobal(); ?>
    
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
                        <p class="profile-hashtag"><?=$value['alias']?></p>
                        <div class="profile-achievements">
                            <div class="achievements">
                                <p><?=$value['total_amigos']?> Buddies</p>
                                <p><?=$value['total_respuestas']?> Posts</p>
                            </div>
                            <div class="barrita"></div>
                            <div class="achievements">
                                <p><?=$value['total_series']?> Series</p>
                                <p><?php echo ($value['total_chips'] == 0)? 0:$value['total_chips'] ?> Chips</p>
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
                                echo $peticionFooter->pintaAmistadNula($value['id'], $paginaActual, $totalPaginas, Peticion::FOOTER_BUDDIES);
                                 
                            //si el user actual (SESIÓN) ha ENVIADO peti al user seleccioando
                            }else if($peticion['estado'] == DWESBaseDatos::PENDIENTE && $peticion['id_emisor'] == $_SESSION['id']) {
                                echo $peticionFooter->pintaAmistadEnviada($value['id'], $paginaActual, $totalPaginas, Peticion::FOOTER_BUDDIES);

                            //si el user actual (SESIÓN) ha RECIBIDO peti del user seleccioando    
                            }else if($peticion['estado'] == DWESBaseDatos::PENDIENTE && $peticion['id_receptor'] == $_SESSION['id']) {
                                echo $peticionFooter->pintaAmistadRecibida($value['id'], $paginaActual, $totalPaginas, Peticion::FOOTER_BUDDIES);

                            //si son AMOGUS  
                            }else if($peticion['estado'] == DWESBaseDatos::ACEPTADA) {
                                echo $peticionFooter->pintaAmistadMutua($value['id'], $paginaActual, $totalPaginas, Peticion::FOOTER_BUDDIES);
                            }
                        }
                    ?>
                </div>
            </div>
        <?php } ?>
    <?php } ?>

    </div>

    <div class="pagination flex-center-center">
        <?php if ($totalPaginas > 1) {echo $paginacion;} ?>
    </div>

<?php

    // ********* FIN BUFFER + LLAMADA AL TEMPLATE **********
    $content = ob_get_contents();
    ob_end_clean();
    require("template.php");
    
?>