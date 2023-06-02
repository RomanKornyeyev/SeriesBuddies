<?php
    
    // ***** FORMULARIOS *****
    use clases\form\campo\Atipo;
    use clases\form\campo\Texto;
    use clases\form\claseMain\Formulario;

    if(isset($serieBuscada)){
        $valorSerieBuscada = $serieBuscada;
    }else{
        $valorSerieBuscada = "";
    }
    
    //si tiene la sesión iniciada, se comprueban las peticiones de amistad
    //si tiene peticiones, le sale una bolita verde
    $bolitaNotificacion = "";
    if ($sesionIniciada) {

        //Obtiene el nombre, el id_emisor, id_receptor y el estado de las peticiones pendientes de ese buddie
        $petis = DWESBaseDatos::obtenPeticionesPendientes($db, $_SESSION['id']);

        if(isset($petis) && count($petis) > 0){
            $bolitaNotificacion = "<div class='bolita-notificacion'>".count($petis)."</div>";
        }
    }
    

    // ========================================= FORM DE BUSCAR SERIE =========================================
    //                             ACTION            METHOD           clases-css-form   ¿Vaciar al validar?   atr-extra(para forms con img)   CAMPOS
    $form = new Formulario("series.php", Formulario::METHOD_POST, ["h-form"],        Formulario::VACIAR_NO,          "",                   array(
        //                       ====================================== COMÚN =======================================================================  //  ======================== ESPECÍFICO ========================
        //                  ¿Puede estar vacío? valor         name   label  clases-css-label  clases-css-wrapper  clases-css-input             tipoCampo       placeholder      regex
        $buscador = new Texto (Atipo::NULL_NO, $valorSerieBuscada,"buscador", "",   ["d-none"],    ["input-wrapper"],  ["h-input"],              Texto::TYPE_TEXT, "Los simpsons",  Texto::DEFAULT_PATTERN_500),
    // === SUBMIT ===
    // claseWrappSubmit                           idSubmit  nameSubm  txtSubmit  clseSubmit
    ), ["h-submit-wrapper"], "buscar", "buscar", "<i class='fa-solid fa-magnifying-glass'></i>", ["btn", "btn--primary", "h-submit-button"]);

    if ($form->validarGlobal()) {
        //redirección
        header('Location: ./series.php?buscador='.$buscador->getValor());
        die();
    }

?>

<header class="header">
    <!-- *** MOBILE LOGO *** -->
    <!-- trigger menu mobile -->
    <a href="index.php">
        <!-- <h1 class="logo logo--mbl">SeriesBuddies</h1> -->
        <img src="upload/logos/logo-mobile.png" alt="Logo SeriesBuddies" class="logo logo--mbl">
    </a>
    <div id="nav-toggle" class="nav-toggle">
        <span class="bar bar--top"></span>
        <span class="bar bar--middle"></span>
        <span class="bar bar--bottom"></span>
    </div>
    <!-- header content (nav, etc.) -->
    <div id="header-content" class="header__content">
        <div class="header-left-content">
            <!-- *** PC LOGO *** -->
            <a href="index.php" class="logo--pc">
                <!-- <h1 class="logo logo--pc">SeriesBuddies</h1> -->
                <img src="upload/logos/logo-principal.png" alt="Logo SeriesBuddies" class="logo logo--pc">
            </a>
            <nav class="nav nav--header">
                <a href="./genders.php" class="nav__link nav__link--header">Géneros</a>
                <a href="./buddies.php" class="nav__link nav__link--header">Buddies</a>
            </nav>
        </div>

        <div class="nav nav--login">
            <!-- search form -->
            <?php $form->pintarGlobal(); ?>
            <!-- user -->
            <?php if($sesionIniciada){?>
                <div class='user-area-wrapper'>
                    <a class='user-area' href='profile.php?id=<?=$usuarioId?>'>
                        <?=$bolitaNotificacion?>
                        <div class='img-perfil-nav'>
                            <img class='img-fit' src='<?=$usuarioImg?>' alt='img-user'>
                        </div>
                        <div class="nav__link">
                            <div class="nav__link--user"><?=$usuarioNombre?></div>
                        </div>
                        
                    </a>
                </div>
            <?php } ?>
           
            <!-- login/logout -->
            <?php if($sesionIniciada){?>
                <a href="./logout.php" class="nav__link nav__link--login">Logout</a>
            <?php }else{ ?>
                <a href="./login.php" class="nav__link nav__link--login">Login</a>
            <?php } ?>
        </div>
    </div>
</header>