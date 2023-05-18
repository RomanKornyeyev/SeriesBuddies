<?php

    // ********* INIT **********
    require_once("../src/init.php");

    // ********* INFO PARA EL TEMPLATE **********
    $tituloHead = "Buddies - SeriesBuddies";
    $estiloEspecifico = "./css/buddies.css";
    $scriptEspecifico = "./js/peticiones.js";
    $scriptLoadMode = "defer";
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
            <div class="buddy__footer-external-layer">
                <div class="buddy__footer-internal-layer">
                    
                    <?php //obtenemos info sobre el estado de petición de amistad ?>
                    <?php $peticion = DWESBaseDatos::obtenPeticion($db, $_SESSION['id'], $value['id']);?>

                    <?php if ($peticion == "" || $peticion == null) { ?>
                        <div class="buddy__footer buddy__footer--primary grid-col-1">
                            <button class="btn btn--card" onclick="subir(this)">
                                <i class="fa-solid fa-id-card"></i>&nbsp;
                                <span class="primary-font">Volver</span>
                                &nbsp;<i class="fa-solid fa-arrow-down"></i>
                            </button>
                        </div>
                        <div class="buddy__footer buddy__footer--primary <?php if($esAdmin) echo "grid-col-3";?>">
                            <a class="btn btn--card" href="#">Conectar</a>
                    <?php }else if($peticion['estado'] == DWESBaseDatos::PENDIENTE && $peticion['id_emisor'] == $_SESSION['id']) { ?>
                        <div class="buddy__footer buddy__footer--primary grid-col-2">
                            <a class="btn btn--card btn--error" href="#">CANCELAR&nbsp;<i class="fa-solid fa-xmark"></i></a>
                            <button class="btn btn--card" onclick="subir(this)">
                                <i class="fa-solid fa-id-card"></i>&nbsp;
                                <span class="primary-font">Volver</span>
                                &nbsp;<i class="fa-solid fa-arrow-down"></i>
                            </button>
                        </div>
                        <div class="buddy__footer buddy__footer--primary <?php if($esAdmin) echo "grid-col-3";?>">
                            <button class="btn btn--card" onclick="bajar(this)">
                                <i class="fa-solid fa-user-group"></i>&nbsp;
                                <span class="primary-font">Enviada</span>
                                &nbsp;<i class="fa-solid fa-arrow-up"></i>
                            </button>
                    <?php }else if($peticion['estado'] == DWESBaseDatos::PENDIENTE && $peticion['id_receptor'] == $_SESSION['id']) { ?>
                        <div class="buddy__footer buddy__footer--primary grid-col-3">
                            <a class="btn btn--card btn--success" href="#">ACEPTAR&nbsp;<i class="fa-solid fa-check"></i></a>
                            <a class="btn btn--card btn--error" href="#">RECHAZAR&nbsp;<i class="fa-solid fa-xmark"></i></a>
                            <button class="btn btn--card" onclick="subir(this)">
                                <i class="fa-solid fa-id-card"></i>&nbsp;
                                <span class="primary-font">Volver</span>
                                &nbsp;<i class="fa-solid fa-arrow-down"></i>
                            </button>
                        </div>
                        <div class="buddy__footer buddy__footer--primary <?php if($esAdmin) echo "grid-col-3";?>">
                            <button class="btn btn--card" onclick="bajar(this)">
                                <i class="fa-solid fa-user-group"></i>&nbsp;
                                <span class="primary-font">Recibida</span>
                                &nbsp;<i class="fa-solid fa-arrow-up"></i>
                            </button>
                        <?php }else if($peticion['estado'] == DWESBaseDatos::ACEPTADA) { ?>
                            <div class="buddy__footer buddy__footer--primary grid-col-2">
                                <a class="btn btn--card btn--error" href="#">ELIMINAR&nbsp;<i class="fa-solid fa-xmark"></i></a>
                                <button class="btn btn--card" onclick="subir(this)">
                                    <i class="fa-solid fa-id-card"></i>&nbsp;
                                    <span class="primary-font">Volver</span>
                                    &nbsp;<i class="fa-solid fa-arrow-down"></i>
                                </button>
                            </div>
                            <div class="buddy__footer buddy__footer--primary <?php if($esAdmin) echo "grid-col-3";?>">
                                <button class="btn btn--card" onclick="bajar(this)">
                                    <i class="fa-solid fa-user-group"></i>&nbsp;
                                    <span class="primary-font">Es amig@</span>
                                    &nbsp;<i class="fa-solid fa-arrow-up"></i>
                                </button>
                        <?php } ?>
                        <a class="btn btn--card" href="./profile.php">Perfil</a>
                        <?php if($esAdmin){ ?>
                            <a class="btn btn--card btn--error" href="#">Eliminar</a>
                        <?php }?>
                    </div>
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