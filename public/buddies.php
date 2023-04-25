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
    <title>Buddies - SeriesBuddies</title>

    <!-- CSS -->
    <link rel="stylesheet" href="./css/general.css">
    <link rel="stylesheet" href="./css/buddies.css">

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
                <h1 class="title title--l text-align-center">BUDDIES</h1>
                <div class="main__content">
                    <!-- entries -->
                    <script>
                        const mainContent = document.querySelector(".main__content");
                        // x3 entries (admin view)
                        for (let i = 0; i < 3; i++) {
                            mainContent.innerHTML += `
                                <div class="card card--buddy">
                                    <div class="buddy__body">
                                        <div class="profile-img">
                                            <img class="img-fit" src="./img/gazpacho.jpg" alt="profile-img">
                                        </div>
                                        <div class="profile-info">
                                            <h2 class="profile-name">Román</h2>
                                            <p class="profile-hashtag">@romanXD</p>
                                            <div class="profile-achievements">
                                                <div class="achievements">
                                                    <p>50 Buddies</p>
                                                    <p>1579 Posts</p>
                                                </div>
                                                <div class="barrita"></div>
                                                <div class="achievements">
                                                    <p>52 Series</p>
                                                    <p>12 Chips</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="buddy__footer grid-col-3">
                                        <div class="button-card">
                                            <a class="btn btn--card" href="./profile.php">Perfil</a>
                                        </div>
                                        <div class="button-card">
                                            <a class="btn btn--card" href="#">Conectar</a>
                                        </div>
                                        <div class="button-card">
                                            <a class="btn btn--card-admin" href="#">Eliminar</a>
                                        </div>
                                    </div>
                                </div>
                            `; 
                        }
                        // x6 entries (user view)
                        for (let i = 0; i < 6; i++) {
                            mainContent.innerHTML += `
                                <div class="card card--buddy">
                                    <div class="buddy__body">
                                        <div class="profile-img">
                                            <img class="img-fit" src="./img/default.png" alt="profile-img">
                                        </div>
                                        <div class="profile-info">
                                            <h2 class="profile-name">Román</h2>
                                            <p class="profile-hashtag">@romanXD</p>
                                            <div class="profile-achievements">
                                                <div class="achievements">
                                                    <p>50 Buddies</p>
                                                    <p>1579 Posts</p>
                                                </div>
                                                <div class="barrita"></div>
                                                <div class="achievements">
                                                    <p>52 Series</p>
                                                    <p>12 Chips</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="buddy__footer">
                                        <div class="button-card">
                                            <a class="btn btn--card" href="./profile.php">Perfil</a>
                                        </div>
                                        <div class="button-card">
                                            <a class="btn btn--card" href="#">Conectar</a>
                                        </div>
                                    </div>
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