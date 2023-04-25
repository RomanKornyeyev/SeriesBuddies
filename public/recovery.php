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
    <title>Recovery</title>

    <!-- CSS -->
    <link rel="stylesheet" href="./css/general.css">
    <link rel="stylesheet" href="./css/login.css">

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
                <h1 class="title title--form">Recovery</h1>
                <p class="extra-form-info">
                    <b>¿Contraseña olvidada?</b> No pasa nada
                    <br>
                    Déjanos tu email y te enviaremos un enlace para recuperar tu contraseña =)
                </p>
                <form action="login.php" method="post" class="form">
                    <div class="input-wrapper">
                        <!-- necesario poner placerholder con un espacio vacío para un trick css -->
                        <input type="text" name="correo" id="correo" class="input shadow-lightgray" placeholder=" ">
                        <label for="correo" class="label label--text">Email</label>
                        <!-- <div class="error">Error del campo</div> -->
                    </div>
                    
                    
                    <div class="input-wrapper input-wrapper--submit">
                        <input type="submit" value="RECUPERAR" class="btn btn--primary shadow-lightgray">
                    </div>
                </form>
                <div class="extra-form-info">
                    ¿La has recordado? <a href="login.php" class="link-enphasis link-body">Inicia sesión</a>
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