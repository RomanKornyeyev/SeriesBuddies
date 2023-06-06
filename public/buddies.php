<?php

    // ***** INIT *****
    require_once("../src/init.php");
    
    // ***** FORMULARIOS *****
    use clases\form\campo\Atipo;
    use clases\form\campo\Texto;
    use clases\form\campo\Numero;
    use clases\form\claseMain\Formulario;

    // ***** PETICIONES *****
    use clases\peticiones\Peticion;
    $peticionFooter = new Peticion($esAdmin);

    // ***** API TMDB *****
    use clases\api_tmdb\TMDB;
    
    $tmdb = new TMDB();

    
    // ========================================= FORM DE BUSCAR BUDDIES =========================================
    //                             ACTION            METHOD           clases-css-form   ¿Vaciar al validar?   atr-extra(para forms con img)   CAMPOS
    $formBuddies = new Formulario("buddies.php", Formulario::METHOD_POST, ["h-form"],        Formulario::VACIAR_NO,          "",                   array(
        //                       ====================================== COMÚN =======================================================================  //  ======================== ESPECÍFICO ========================
        //                ¿Puede estar vacío?       valor               name        label  clases-css-label  clases-css-wrapper  clases-css-input             tipoCampo       placeholder      regex
        $buddies = new Texto        (Atipo::NULL_NO, $_GET['buddies'],              "buddies",  "",  ["d-none"],    ["input-wrapper"],  ["h-input"],              Texto::TYPE_TEXT, "Busca a un buddy...",  Texto::DEFAULT_PATTERN_25),
        $hiddenIdSerie = new Numero (Atipo::NULL_SI, $_GET['id-serie'], "id-serie", "",  ["d-none"],    ["d-none"],  ["d-none"],           Numero::TYPE_NUMBER, Numero::MIN_DEFAULT_0, 999999999)
    // === SUBMIT ===
    // claseWrappSubmit      idSubmit  nameSubm  txtSubmit  clseSubmit
    ), ["h-submit-wrapper"], "buscar-buddies", "buscar-buddies", "<i class='fa-solid fa-magnifying-glass'></i>", ["btn", "btn--primary", "h-submit-button"]);

    //hacemos un redirect a la página con los parámetros solicitados (id-serie opcional)
    //pequeña ñapa para que no quede fea la URL con el GET (por el botón submit)
    if ($formBuddies->validarGlobal()) {
        $opcionExtra = ($hiddenIdSerie->getValor() != "")? 'id-serie='.$hiddenIdSerie->getValor().'&' : "";

        //redirección
        header('Location: ./buddies.php?'.$opcionExtra.'buddies='.$buddies->getValor());
        die();
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

    $nbSerie = "";
    // si hay un get de id-serie, significa que están filtrados por serie
    if (isset($_GET['id-serie'])) {
        $idSerie = $_GET['id-serie'];

        if (isset($_GET['buddies'])) {
            //obtenemos todos los users filtrados por serie + busqueda
            $consulta = DWESBaseDatos::obtenListadoBuddiesBusquedaPorSerie($db, $_GET['buddies'], $idSerie, $registroInicial);
            $totalRegistros = DWESBaseDatos::obtenTotalBuddiesBusquedaSerie ($db, $_GET['buddies'], $idSerie);

            //paginación
            $argumentos = array(
                "id-serie" => $idSerie,
                "buddies" => $_GET['buddies']
            );
        }else{
            //obtenemos todos los users filtrados por serie
            $consulta = DWESBaseDatos::obtenListadoBuddiesPorSerie($db, $idSerie, $registroInicial);
            $totalRegistros = DWESBaseDatos::obtenTotalBuddiesSerie ($db, $idSerie);
            
            //paginación
            $argumentos = array(
                "id-serie" => $idSerie
            );
        }

        $nbSerie = $tmdb->getNbSeriePorId($idSerie);

    //si está filtrado por buscador, pero no tiene id-serie
    }else if (!isset($_GET['id-serie']) && isset($_GET['buddies'])){        
        //Devuelvo todos los usuarios que coincidan con esa busqueda
        $consulta = DWESBaseDatos::obtenListadoBuddiesBusqueda($db, $_GET['buddies'], $registroInicial);
        $totalRegistros = DWESBaseDatos::obtenTotalBuddiesBusqueda ($db, $_GET['buddies']);        

        //paginación
        $argumentos = array(
            "buddies" => $buddies->getValor()
        );
    
    //si no hay NINGÚN FILTRO
    }else{
        //Devuelvo todos los usuarios + total registros
        $consulta = DWESBaseDatos::obtenListadoBuddies ($db, $registroInicial);
        $totalRegistros = DWESBaseDatos::obtenTotalUsuarios($db)['total_usuarios'];

        //paginación
        $argumentos = array();
    }
    
    //Total de paginas que hay que mostrar
    $totalPaginas = ceil($totalRegistros / $registrosPagina);
    
    if ($paginaActual > $totalPaginas) {
        $paginaActual = 1;
    }

    //paginación
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

    <?php // ***** AUX NAV ***** ?>
    <nav class="nav-aux" aria-label="Main">
        <a href="./buddies.php" class="primary-font primary-color">Buddies</a>
        <?php if (isset($_GET['id-serie'])) { ?>
            <span class="primary-font color-white">&gt;</span>
            <a href="./buddies.php?id-serie=<?=$_GET['id-serie']?>" class="primary-font primary-color"><?=$nbSerie?></a>
        <?php } 
        if (isset($_GET['buddies'])) { ?>
            <span class="primary-font color-white">&gt;</span>
            <a href="" class="primary-font primary-color">Resultados de "<?=$_GET['buddies']?>"</a>
        <?php } ?>
    </nav>

    <h1 class="title title--l text-align-center">BUDDIES</h1>

    <!-- pagination + search form -->
    <div class="pagination pagination--plus-response">
        <!-- search form -->
        <?php $formBuddies->pintarGlobal(); ?>
        <?php if ($totalPaginas >= 1) {echo $paginacion;} ?>
    </div>
    
    
    <div class="main__content">

    <?php // ***** GENERACIÓN DE USERS ***** ?>
    <?php //por cada user pintamos una tarjeta ?>
    <?php foreach ($consulta as $value){?>
        <?php 
            if($value['privilegio'] == DWESBaseDatos::ADMIN){
                $adminTarjeta = true;
            }else{
                $adminTarjeta = false;
            }
        ?>
        <!-- CARD (GLOBAL) -->
        <div class="card card--buddy">

            <!-- CARD BODY -->
            <div class="buddy__body">
                <div class="profile-img-wrapper <?php if($adminTarjeta){ echo "profile-img-wrapper-admin-buddies";}?>">
                    <div class="profile-img">
                        <img class="img-fit" src="<?=$value['img']?>" alt="profile-img">
                    </div>
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
                    if (!$sesionIniciada || $_SESSION['id'] == $value['id']) {
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