<?php

    // ********* INIT **********
    require_once("../src/init.php");

    // ********* INFO PARA EL TEMPLATE **********
    $tituloHead = "Buddies - SeriesBuddies";
    $estiloEspecifico = "./css/buddies.css";
    $scriptEspecifico = "";
    $scriptLoadMode = "";
    $content;

    // ********* COMIENZO BUFFER **********
    ob_start();
?>

    <h1 class="title title--l text-align-center">BUDDIES</h1>
    <div class="main__content">




    <?php 
    //obtenemos todos los users
    $consulta = DWESBaseDatos::obtenUsuarios($db);
    //por cada user pintamos una tarjeta
    foreach ($consulta as $value) 
    {?>
        
        <div class="card card--buddy">
            <div class="buddy__body">
                <div class="profile-img">
                    <img class="img-fit" src="./upload/perfiles/gazpacho.jpg" alt="profile-img">
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
        
    <?php } ?>
    




    </div>

<?php

    // ********* FIN BUFFER + LLAMADA AL TEMPLATE **********
    $content = ob_get_contents();
    ob_end_clean();
    require("template.php");
    
?>