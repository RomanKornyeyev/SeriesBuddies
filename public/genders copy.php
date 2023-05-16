<?php

    require_once("../src/init.php");
    use form\claseMain\TMDB;
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
    <title>Géneros - SeriesBuddies</title>

    <!-- CSS -->
    <link rel="stylesheet" href="./css/general.css">
    <link rel="stylesheet" href="./css/genders.css">

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
                <h1 class="title title--l text-align-center">GÉNEROS</h1>
                <div class="main__content">
                    <!-- entries -->
                    <script>
                        // x3 entries (admin view)
                        const mainContent = document.querySelector(".main__content");
                        for (let i = 0; i < 3; i++) {
                            mainContent.innerHTML += `
                                <div class="box">
                                    <a href="./series.php" class="box-body-wrapper">
                                        <div class="box__body box__body--gender">
                                            <img class="img-fit img-gender" src="./upload/stranger_things.png" alt="género">
                                            <div class="box-body__info">
                                                <h2 class="title title-gender">Sci-fi</h2>
                                                <p class="text-white"> 1578 resultados &gt;</p>
                                            </div>
                                        </div>
                                    </a>
                                    <form action="" method="post" class="box__admin">
                                        <input type="hidden" name="1" id="1">
                                        <button type="submit" class="btn btn--card-admin">ELIMINAR</button>
                                    </form>
                                </div>
                            `;                            
                        }
                        // x6 entries (user view)
                        for (let i = 0; i < 6; i++) {
                            mainContent.innerHTML += `
                                <div class="box">
                                    <a href="./series.php" class="box-body-wrapper">
                                        <div class="box__body box__body--gender">
                                            <img class="img-fit img-gender" src="./upload/stranger_things.png" alt="género">
                                            <div class="box-body__info">
                                                <h2 class="title title-gender">Sci-fi</h2>
                                                <p class="text-white"> 1578 resultados &gt;</p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            `; 
                        }
                    </script>
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