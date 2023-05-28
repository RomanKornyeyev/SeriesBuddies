<?php
    
    // ***** FORMULARIOS *****
    use clases\form\campo\Atipo;
    use clases\form\campo\Fecha;
    use clases\form\campo\Multiple;
    use clases\form\campo\Numero;
    use clases\form\campo\Texto;
    use clases\form\campo\File;
    use clases\form\claseMain\Formulario;
    

    // ========================================= FORM DE LOGIN =========================================
    //                             ACTION            METHOD           clases-css-form   ¿Vaciar al validar?   atr-extra(para forms con img)   CAMPOS
    $formulario = new Formulario("series.php", Formulario::METHOD_POST, ["form"],        Formulario::VACIAR_NO,          "",                   array(
        //                       ====================================== COMÚN =======================================================================  //  ======================== ESPECÍFICO ========================
        //                  ¿Puede estar vacío? valor  name    label         clases-css-label         clases-css-wrapper       clases-css-input            tipoCampo    placeholder    regex
        $buscador = new Texto (Atipo::NULL_NO, $serieBuscada,"buscador", "buscador",   ["label","label--text"],    ["input-wrapper"],  ["input","shadow-lightgray"],  Texto::TYPE_TEXT, " ",  Texto::DEFAULT_PATTERN_500),
    // === SUBMIT ===
    // claseWrappSubmit                           idSubmit  nameSubm  txtSubmit  clseSubmit
    ), ["input-wrapper","input-wrapper--submit"], "buscar", "buscar", "BUSCAR", ["btn", "btn--primary", "shadow-lightgray"]);

    if ($formulario->validarGlobal()) {
        //redirección
        header('Location: ./series.php?buscador='.$buscador->getValor());
        die();
    }

?>

<header class="header">
    <!-- *** MOBILE LOGO *** -->
    <!-- trigger menu mobile -->
    <a href="index.php" class="d-flex flex-center-center" >
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
        <nav class="nav nav--header">
            <a href="./genders.php" class="nav__link nav__link--header">Géneros</a>
            <a href="./buddies.php" class="nav__link nav__link--header">Buddies</a>
        </nav>
        <!-- *** PC LOGO *** -->
        <a href="index.php">
            <!-- <h1 class="logo logo--pc">SeriesBuddies</h1> -->
            <img src="upload/logos/logo-principal.png" alt="Logo SeriesBuddies" class="logo logo--pc">
        </a>

        <?php $formulario->pintarGlobal(); ?>

        <div class="nav nav--login">
            <!-- user -->
            <div class='user-area-wrapper'>
                <?php if($sesionIniciada){?>
                    <a class='user-area' href='profile.php?id=<?=$usuarioId?>'>
                        <div class='img-perfil-nav'>
                            <img class='img-fit' src='<?=$usuarioImg?>' alt='img-user'>
                        </div>
                        <div class="nav__link">
                            <div class="nav__link--user"><?=$usuarioNombre?></div>
                        </div>
                    </a>
                <?php } ?>
            </div>
           
            <!-- login/logout -->
            <?php if($sesionIniciada){?>
                <a href="./logout.php" class="nav__link nav__link--login">Logout</a>
            <?php }else{ ?>
                <a href="./login.php" class="nav__link nav__link--login">Login</a>
            <?php } ?>
        </div>
    </div>
</header>