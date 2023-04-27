<?php

    require("../src/init.php");
    

?>
<!DOCTYPE html>
<html lang="es-ES">
<head>
    <!-- META -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Intercambia ideas sobre tus series favoritas">
    <meta name="keywords" content="series, opiniones, ideas">
    <meta name="author" content="Anabel, Román">
    <meta name="copyright" content="Anabel, Román">

    <!-- TITLE -->
    <title>SeriesBuddies - Comenta tus series favoritas</title>

    <!-- CSS -->
    <link rel="stylesheet" href="./css/general.css">
    <link rel="stylesheet" href="./css/index.css">

    <!-- JS -->
    <script src="./js/header.js" defer></script>
    <script src="./js/index.js" defer></script>
</head>
<body>
    <!-- global container -->
    <div class="global-container">
        <!-- header -->
        <?php include_once('header.php'); ?>

        <!-- body (central container) -->
        <div class="container">
            <!-- main -->
            <main class="main">
                <!-- img hero -->
                <div class="hero" id="hero">
                    <img class="img-fit" src="./upload/hero-1.png" alt="seriesbuddies">

                    <!-- comment top -->
                    <div class="row row--top" id="row-top">
                        <div class="row__user">
                            <div class="user__img user__img--top">
                                <img class="img-fit img-user-landing" src="./upload/perfiles/default.png" alt="user">
                            </div>
                            <div class="user__medals">
                                <div class="medal medal--top"></div>
                                <div class="medal medal--top"></div>
                                <div class="medal medal--top"></div>
                            </div>
                        </div>
                        <div class="row__post row__post--top">
                            <p class="post__date">15/03/2023</p>
                            <p class="post__content">Lorem ipsum dolor sit amet consectetur adipisicing elit. Nobis, doloremque!</p>
                        </div>
                    </div>

                    <!-- comment bottom -->
                    <div class="row row--bottom" id="row-bottom">
                        <div class="row__user">
                            <div class="user__img user__img--bottom">
                                <img class="img-fit img-user-landing" src="./upload/perfiles/default.png" alt="user">
                            </div>
                            <div class="user__medals">
                                <div class="medal medal--bottom"></div>
                                <div class="medal medal--bottom"></div>
                                <div class="medal medal--bottom"></div>
                            </div>
                        </div>
                        <div class="row__post row__post--bottom">
                            <p class="post__date">15/03/2023</p>
                            <p class="post__content">Lorem ipsum dolor sit amet consectetur adipisicing elit. Nobis, doloremque!</p>
                        </div>
                    </div>
                </div>

                <!-- texto -->
                <div class="landing-info">
                    <div class="landing-info__content">
                        <h1 class="title title--landing">
                            INTERCAMBIA IDEAS SOBRE
                            <br>
                            <span class="font-size-ultra-big">
                                TUS SERIES FAVORITAS
                            </span>
                        </h1>
                        <div class="landing-buttons">
                            <a href="login.php" class="btn btn--primary">Inicia sesión</a>
                            <a href="register.php" class="btn btn--outline">Hazte buddy</a>
                        </div>
                    </div>
                </div>
            </main>

            <!-- bg fixed -->
            <div class="bg-fixed"></div>
        </div>
       

        <!-- footer -->

    </div>
</body>
</html>