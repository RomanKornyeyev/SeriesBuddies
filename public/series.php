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
    <link rel="stylesheet" href="./css/series.css">

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
                <h1 class="title title--l text-align-center">Series de ficción</h1>
                <div class="card">
                    <div class="card__serie-img">
                        <img class="img-fit" src="./img/stranger_things-cover.jpg" alt="serie-img">
                    </div>
                    <div class="card__serie-info">
                        <div class="header__serie">
                            <h2 class="title title--serie">Stranger Things</h2>
                            <div class="extra-actions">
                                <!-- <a href="#" class="btn btn--outline-info btn--border-l btn--bold">#ficcion</a>
                                <a href="#" class="btn btn--outline-info btn--border-l btn--bold">#aventura</a> -->
                                <a href="#" class="btn btn--secondary btn--bold">Modificar</a>
                                <a href="#" class="btn btn--error btn--bold">Eliminar</a>
                            </div>
                        </div>
                        <div class="body__serie">
                            <p class="text text--serie">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam ac augue lacus.
                                Ut aliquet orci lectus. Pellentesque consequat sapien massa, in finibus odio rutrum ut. Donec a diam
                                vulputate eros tincidunt cursus nec a massa. Vivamus sed rutrum justo. Donec sapien justo, mollis a
                                libero ac, dictum viverra tortor. Nulla at fringilla dui. Lorem ipsum dolor sit amet, consectetur
                                adipiscing elit.</p>
                        </div>
                        <div class="footer__serie">
                            <a href="#">
                                <div class="icon__buddy"></div>
                                <div class="icon__buddy"></div>
                                <div class="icon__buddy"></div>
                                <p class="info info--serie">Super buddies</p>
                            </a>
                            <a href="./feed.php">
                                <p class="info info--serie">1579 comentarios &gt;</p>
                            </a>
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

    <script>
        let main = document.querySelector('.main');
        for (let i = 0; i < 4; i++) {
            main.innerHTML+=`
            <div class="card">
                <div class="card__serie-img">
                    <img class="img-fit" src="./img/stranger_things-cover.jpg" alt="serie-img">
                    </div>
                    <div class="card__serie-info">
                        <div class="header__serie">
                            <h2 class="title title--serie">Stranger Things</h2>
                                <div class="extra-actions">
                                    <!-- <a href="#" class="btn btn--primary btn--bold">Modificar</a>
                                    <a href="#" class="btn btn--error btn--bold">Eliminar</a> -->
                                </div>
                            </div>
                        <div class="body__serie">
                            <p class="text text--serie">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam ac augue lacus. Ut aliquet orci lectus. Pellentesque consequat sapien massa, in finibus odio rutrum ut. Donec a diam vulputate eros tincidunt cursus nec a massa. Vivamus sed rutrum justo. Donec sapien justo, mollis a libero ac, dictum viverra tortor. Nulla at fringilla dui. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                        </div>
                        <div class="footer__serie">
                            <a href="#">
                                <div class="icon__buddy"></div>
                                <div class="icon__buddy"></div>
                                <div class="icon__buddy"></div>
                                <p class="info info--serie">Super buddies</p>
                            </a>
                            <a href="./feed.php"><p class="info info--serie">1579 comentarios &gt;</p></a>
                        </div>  
                    </div>
                </div>
                                    `;
        }
        main.innerHTML+=`
        <div class="pagination">
            <a href="#" class="btn btn--primary btn--sm">&lt;</a>
            <a href="#" class="btn btn--outline btn--sm">1</a>
            <a href="#" class="btn btn--primary btn--sm">2</a>
            <a href="#" class="btn btn--outline btn--sm">3</a>
            <a href="#" class="btn btn--outline btn--sm">4</a>
            <a href="#" class="btn btn--outline btn--sm">5</a>
            <a href="#" class="btn btn--primary btn--sm">&gt;</a>
        </div>`;
    </script>
</body>
</html>