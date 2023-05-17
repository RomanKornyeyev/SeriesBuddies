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
                    <img class="img-fit" src="<?=$value['img']?>" alt="profile-img">
                </div>
                <div class="profile-info">
                    <h2 class="profile-name"><?=$value['nombre']?></h2>
                    <p class="profile-hashtag">@<?=$value['nombre']?></p>
                    <div class="profile-achievements">
                        <div class="achievements">
                            <p>xx Buddies</p>
                            <p>xxxx Posts</p>
                        </div>
                        <div class="barrita"></div>
                        <div class="achievements">
                            <p>xx Series</p>
                            <p>xx Chips</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="buddy__footer <?php if($esAdmin){ echo 'grid-col-3';}?>">
                <div class="button-card">
                    <a class="btn btn--card" href="./profile.php">Perfil</a>
                </div>
                <div class="button-card">
                    <?php 

                    //obtenemos info sobre el estado de petición de amistad
                    $peticion = DWESBaseDatos::obtenPeticion($db, $_SESSION['id'], $value['id']);
                    
                    if ($peticion == "" || $peticion == null) { ?>
                        <a class="btn btn--card" href="#">Conectar</a>
                    <?php }else if($peticion['estado'] == DWESBaseDatos::PENDIENTE && $peticion['id_remitente'] == $_SESSION['id']) { ?>
                        <a class="btn btn--card" href="#">Enviada</a>
                    <?php }else if($peticion['estado'] == DWESBaseDatos::PENDIENTE && $peticion['id_receptor'] == $_SESSION['id']) { ?>
                        <a class="btn btn--card" href="#">Aceptar</a>
                    <?php }else if($peticion['estado'] == DWESBaseDatos::ACEPTADA) { ?>  
                        <a class="btn btn--card" href="#">Eliminar</a>
                    <?php } ?>
                    
                </div>
                <?php if($esAdmin){ ?>
                    <div class="button-card">
                        <a class="btn btn--card-admin" href="#">Eliminar</a>
                    </div>
                <?php }?>
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