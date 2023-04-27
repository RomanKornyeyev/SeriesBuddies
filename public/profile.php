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
    <link rel="stylesheet" href="./css/profile.css">

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
                    <div class="card__user-img">
                        <div class="profile-img">
                            <img class="img-fit" src="./upload/perfiles/default.png" alt="profile-img">
                        </div>
                    </div>
                    <div class="card__user-bio">
                        <div class="card__user-info">
                            <h1 class="title title--user">Anabelix</h1>
                            <p class="info info--user">anabelpedrajas@hotmail.com</p>
                            <p class="info info--user">Se unió el 2/02/2023</p>
                            <p class="text text--user">Lorem ipsum dolor sit amet consectetur adipisicing elit. Minima molestias ullam nesciunt illum natus rerum corporis.</p>
                        </div>
                        
                        <div class="button-card">
                            <a class="btn btn--card" href="#">Conectar</a>
                        </div>
                    </div>
                </div>

                <div class="carousel">
                    <div class="carousel carousel__container">
                        <h2 class="title title--carousel">MIS SERIES</h2>
                        <div class="img-line">
                            <a href="#" class="btn btn--carousel">&lt;</a>
                            <div class="img img--carousel"><img src="./upload/image_default.jpg" alt="" srcset=""></div>
                            <div class="img img--carousel"><img src="./upload/image_default.jpg" alt="" srcset=""></div>
                            <div class="img img--carousel"><img src="./upload/image_default.jpg" alt="" srcset=""></div>
                            <div class="img img--carousel"><img src="./upload/image_default.jpg" alt="" srcset=""></div>
                            <a href="#" class="btn btn--carousel">&gt;</a>
                        </div>
                    </div>
                    
                    <div class="carousel carousel__container">
                        <h2 class="title title--carousel">MIS BUDDIES</h2>
                        <div class="img-line">
                            <a href="#" class="btn btn--carousel">&lt;</a>
                            <div class="img img--carousel"><img src="./upload/image_default.jpg" alt="" srcset=""></div>
                            <div class="img img--carousel"><img src="./upload/image_default.jpg" alt="" srcset=""></div>
                            <div class="img img--carousel"><img src="./upload/image_default.jpg" alt="" srcset=""></div>
                            <div class="img img--carousel"><img src="./upload/image_default.jpg" alt="" srcset=""></div>
                            <a href="#" class="btn btn--carousel">&gt;</a>
                        </div>
                    </div>
                    
                    <div class="carousel carousel__container">
                        <h2 class="title title--carousel">MIS CHIPS</h2>
                        <div class="img-line">
                            <a href="#" class="btn btn--carousel">&lt;</a>
                            <div class="img img--carousel"><img src="./upload/image_default.jpg" alt="" srcset=""></div>
                            <div class="img img--carousel"><img src="./upload/image_default.jpg" alt="" srcset=""></div>
                            <div class="img img--carousel"><img src="./upload/image_default.jpg" alt="" srcset=""></div>
                            <div class="img img--carousel"><img src="./upload/image_default.jpg" alt="" srcset=""></div>
                            <a href="#" class="btn btn--carousel">&gt;</a>
                        </div>
                    </div>
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