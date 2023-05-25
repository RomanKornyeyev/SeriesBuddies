<?php

    // ***** INIT *****
    require_once("../src/init.php");

    // ***** PETICIONES *****
    use clases\peticiones\Peticion;

    $peticionFooter = new Peticion($esAdmin);

    if ($sesionIniciada) {
        if (isset($_POST['objeto'])) {
            if (isset($_POST['id'])) {
                switch ($_POST['objeto']) {
                    // ***** ELIMINACIÓN USERS (SOLO ADMIN O ID=ID) *****
                    case 'usuario':
                        if ($esAdmin || $_SESSION['id'] == $_POST['id']) {
                            //SELECT FROM DE LA SIGUIENTE PAGINA
                            //Si son distintas hay que pillar el primer registro de la siguiente pagina +1
                            if ($_POST['pagina_actual'] < $_POST['total_paginas']) {
                                //Comentarios por pagina a mostrar
                                $registrosPagina = DWESBaseDatos::REGISTROS_POR_PAGINA;

                                //Registro/comentario desde el que empezar a recorrer la tabla
                                $registroInicial = $_POST['pagina_actual']*$registrosPagina;
                                
                                $consulta = DWESBaseDatos::obtenListadoBuddies($db, $registroInicial);

                                // ***** ESTADO DE LA PETICIÓN *****
                                if (!$sesionIniciada) {
                                    $futer = $peticionFooter->pintaSesionNoIniciada($consulta[0]['id']);

                                //si el user SI TIENE la sesión iniciada
                                }else{

                                    //obtenemos info sobre el estado de petición de amistad
                                    $peticion = DWESBaseDatos::obtenPeticion($db, $_SESSION['id'], $consulta[0]['id']);

                                    //si ninguno ha mandado petición de amistad
                                    if ($peticion == "" || $peticion == null) {
                                        $futer = $peticionFooter->pintaAmistadNula($consulta[0]['id'], $_POST['pagina_actual'], $_POST['total_paginas']);
                                        
                                    //si el user actual (SESIÓN) ha ENVIADO peti al user seleccioando
                                    }else if($peticion['estado'] == DWESBaseDatos::PENDIENTE && $peticion['id_emisor'] == $_SESSION['id']) {
                                        $futer = $peticionFooter->pintaAmistadEnviada($consulta[0]['id'], $_POST['pagina_actual'], $_POST['total_paginas']);

                                    //si el user actual (SESIÓN) ha RECIBIDO peti del user seleccioando    
                                    }else if($peticion['estado'] == DWESBaseDatos::PENDIENTE && $peticion['id_receptor'] == $_SESSION['id']) {
                                        $futer = $peticionFooter->pintaAmistadRecibida($consulta[0]['id'], $_POST['pagina_actual'], $_POST['total_paginas']);

                                    //si son AMOGUS  
                                    }else if($peticion['estado'] == DWESBaseDatos::ACEPTADA) {
                                        $futer = $peticionFooter->pintaAmistadMutua($consulta[0]['id'], $_POST['pagina_actual'], $_POST['total_paginas']);
                                    }
                                }

                                
                                echo "
                                    <!-- CARD (GLOBAL) -->
                                    <div class='card card--buddy'>
                        
                                        <!-- CARD BODY -->
                                        <div class='buddy__body'>
                                            <div class='profile-img'>
                                                <img class='img-fit' src='".$consulta[0]['img']."' alt='profile-img'>
                                            </div>
                                            <div class='profile-info'>
                                                <h2 class='profile-name'>".$consulta[0]['nombre']."</h2>
                                                <p class='profile-hashtag'>".$consulta[0]['alias']."</p>
                                                <div class='profile-achievements'>
                                                    <div class='achievements'>
                                                        <p>".$consulta[0]['total_amigos']." Buddies</p>
                                                        <p>".$consulta[0]['total_respuestas']." Posts</p>
                                                    </div>
                                                    <div class='barrita'></div>
                                                    <div class='achievements'>
                                                        <p>".$consulta[0]['total_series']." Series</p>
                                                        <p>xx Chips</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                        
                                        <!-- CARD FOOTER -->
                                        <div class='buddy__footer-external-layer'>
                                        ".$futer."
                                        </div>
                                    </div>
                                ";
                            }

                            //QUERY
                            //BORRA USER SELECCIONADO
                            $db->ejecuta(
                                "DELETE FROM usuarios WHERE id=?;",
                                $_POST['id']
                            );
                            die();
                        }else{
                            echo "error";
                            die();
                        }
                        break;



                    // ***** BORRAR RESPUESTA *****
                    case 'respuesta':
                        $db->ejecuta(
                            'SELECT u.id FROM respuestas r INNER JOIN usuarios u ON r.id_usuario=u.id WHERE r.id=?',
                            $_POST['id']
                        );
                        $consulta = $db->obtenElDato();
                        if($_SESSION['id'] == $consulta['id'] || $esAdmin){
                            if ($_POST['pagina_actual'] < $_POST['total_paginas']) {
                                //registros a mostrar
                                $registrosPagina = DWESBaseDatos::REGISTROS_POR_PAGINA;

                                //registro inicial
                                $registroInicial = $_POST['pagina_actual']*$registrosPagina;
                                
                                //obtenemos las respuestas de la siguiente página
                                $consulta = DWESBaseDatos::obtenRespuestasSerie ($db, $_POST['id_serie'], $registroInicial);

                                $botones = "";

                                if($consulta[0]['id_user'] == $_SESSION['id'] || $esAdmin) {
                                    $botones = "
                                        <a href='./feed.php?id=".$_POST['id_serie']."&action=editando&id_respuesta=".$consulta[0]['id_respuesta']."&pagina=".$_POST['pagina_actual']."' class='btn btn--secondary btn--sm btn--bold'>Editar</a>
                                        <button class='btn btn--error btn--sm btn--bold' onclick='eliminar(this, ".$consulta[0]['id_respuesta'].", ".$_POST['id_serie'].", ".$_POST['pagina_actual'].", ".$_POST['total_paginas'].")'>Eliminar</button>
                                    ";
                                    
                                }

                                echo "
                                    <div class='card'>
                                        <div class='card__post'>
                                            <div class='card__post-img'>
                                                <div class='img-user-post'>
                                                    <img class='img-fit' src='".$consulta[0]['img']."' alt='serie-img'>
                                                </div>
                                                <h2 class='title title--user'>".$consulta[0]['nombre']."</h2>
                                                <div class='icon'>
                                                    <div class='icon__chip'></div>
                                                    <div class='icon__chip'></div>
                                                    <div class='icon__chip'></div>
                                                </div>
                                                <div class='user--responsive'>
                                                    <h2 class='title title--user'>".$consulta[0]['nombre']."</h2>
                                                    <div class='icon'>
                                                        <div class='icon__chip'></div>
                                                        <div class='icon__chip'></div>
                                                        <div class='icon__chip'></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class='card__post-comment'>
                                                <div class='info info--comment'>
                                                    <div class='date-post'>Publicado el ".$consulta[0]['fecha_formateada']."</div>
                                                    <div class='admin-area'>".
                                                        $botones
                                                    ."</div>
                                                </div>
                                                <p class='text text--comment'>".$consulta[0]['contenido']."</p>
                                            </div>
                                        </div>
                                    </div>
                                ";
                            }

                            DWESBaseDatos::eliminaRespuesta($db, $_POST['id']);
                        }
                        die();
                    break;

                    
                    default:
                        # code...
                        break;
                }

            // --- NO HAY ID DEL OBJETO A ELIMINAR ---
            }else{
                echo "error";
                die();
            }
        // --- NO HAY OBJETO A ELIMINAR EN EL POST ---
        }else{
            echo "error";
            die();
        }
    // --- SESIÓN NO INICIADA ---
    }else{
        echo "error";
        die();
    }
?>