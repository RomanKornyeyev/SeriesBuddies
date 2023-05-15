<header class="header">
    <!-- trigger menu mobile -->
    <a href="index.php"><h1 class="logo logo--mbl">SeriesBuddies</h1></a>
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
        <a href="index.php">
            <!-- <h1 class="logo logo--pc">SeriesBuddies</h1> -->
            <img src="upload/logos/logo-principal.png" alt="Logo SeriesBuddies">
        </a>
        <div class="nav nav--login">
            <!-- user -->
            <div class='user-area-wrapper'>
                <?php if($sesionIniciada){?>
                    <a class='user-area' href='profile.php?usuario=<?=$usuarioId?>'>
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