<?php

    require_once("../src/init.php");

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
    <title>Login</title>

    <!-- CSS -->
    <link rel="stylesheet" href="./css/general.css">
    <link rel="stylesheet" href="./css/buttons.css">
    <link rel="stylesheet" href="./css/feed.css">

    <!-- JS -->
    <script src="./js/header.js" defer></script>
</head>
<body>
    <!-- global container -->
    <div class="global-container">
        <!-- header -->
        <?php include_once('header.php'); ?>

        <!-- body (central container) -->
        <div class="container limit-width">
            <!-- main -->
            <main class="main">
                <div class="card">
                    <div class="card__serie-img">
                        <img class="img-fit" src="./upload/mc2.jpg" alt="serie-img">
                        <h2 class="title title--serie">Kingdom</h2>
                    </div>
                    <div class="card__serie-info">
                        <div class="info info--serie">
                            <p class="text text--serie">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam ac augue lacus. Ut aliquet orci lectus. Pellentesque consequat sapien massa, in finibus odio rutrum ut. Donec a diam vulputate eros tincidunt cursus nec a massa. Vivamus sed rutrum justo. Donec sapien justo, mollis a libero ac, dictum viverra tortor. Nulla at fringilla dui. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                            <a href="#" class="btn btn--outline_filter">#ficcion</a>
                            <a href="#" class="btn btn--outline_filter">#terror</a>
                        </div>
                        <a href="#" class="links__buddy">
                            <div class="icon">
                                <div class="icon__buddy"></div>
                                <div class="icon__buddy"></div>
                                <div class="icon__buddy"></div>
                                <div class="icon__buddy"></div>
                            </div>
                            <p class="info info--buddy">Ver todos los buddies &gt;</p>
                        </a> 
                    </div>
                </div>

                <div class="card">
                    <div class="card__post">
                        <div class="card__post-img">
                            <div class="img-user-post">
                                <img class="img-fit" src="./upload/perfiles/default.png" alt="serie-img">
                            </div>
                            <h2 class="title title--user">Román</h2>
                            <div class="icon">
                                <div class="icon__chip"></div>
                                <div class="icon__chip"></div>
                                <div class="icon__chip"></div>
                            </div>
                            <div class="user--responsive">
                                <h2 class="title title--user">Román</h2>
                                <div class="icon">
                                    <div class="icon__chip"></div>
                                    <div class="icon__chip"></div>
                                    <div class="icon__chip"></div>
                                </div>
                            </div>
                        </div>
                        <div class="card__post-comment">
                            <div class="info info--comment">
                                <div class="date-post">Se unió el 2/02/2023</div>
                                <div class="admin-area">
                                    <a href="" class="btn btn--secondary btn--sm btn--bold">Editar</a>
                                    <a href="" class="btn btn--error btn--sm btn--bold">Eliminar</a>
                                </div>
                            </div>
                            <p class="text text--comment">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam ac augue lacus. Ut aliquet orci lectus. Pellentesque consequat sapien massa, in finibus odio rutrum ut. Donec a diam vulputate eros tincidunt cursus nec a massa. Vivamus sed rutrum justo. Donec sapien justo, mollis a libero ac, dictum viverra tortor. Nulla at fringilla dui. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                        </div>
                    </div>
                </div>

                <div class="pagination">
                    <a href="#" class="btn btn--primary btn--sm">&lt;</a>
                    <a href="#" class="btn btn--outline btn--sm">1</a>
                    <a href="#" class="btn btn--primary btn--sm">2</a>
                    <a href="#" class="btn btn--outline btn--sm">3</a>
                    <a href="#" class="btn btn--outline btn--sm">4</a>
                    <a href="#" class="btn btn--outline btn--sm">5</a>
                    <a href="#" class="btn btn--primary btn--sm">&gt;</a>
                </div>
            </main>

            <!-- posible aside / divs con info extra -->

             <!-- bg fixed -->
            <div class="bg-fixed"></div>
        </div>
       

        <!-- footer -->
        <?php include_once('footer.php'); ?>
    </div>
</body>
</html>