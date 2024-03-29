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
    <title><?=$tituloHead?></title>
    <link rel="icon" type="image/png" href="upload/logos/logo-mobile.png">

    <!-- CSS -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="./css/general.css">
    <link rel="stylesheet" href="<?=$estiloEspecifico?>">

    <!-- JS -->
    <script src="./js/header.js" defer></script>
    <script src="<?=$scriptEspecifico?>" <?=$scriptLoadMode?>></script>
</head>
<body>
    <!-- global container -->
    <div class="global-container">
        <!-- header -->
        <?php include_once('header.php'); ?>

        <!-- body (central container) -->
        <div class="container">
            <!-- main -->
            <main class="main" id="main">



                <!-- contenido para el template -->
                <?=$content?>




            </main>

             <!-- bg fixed -->
            <div class="bg-fixed"></div>
        </div>

    </div>
</body>
</html>