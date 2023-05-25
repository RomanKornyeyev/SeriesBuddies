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
                            if ($_POST['pagina_actual'] != $_POST['total_paginas']) {
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
                        $db->ejecuta('SELECT * FROM usuarios WHERE ');
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