<?php

    // ********* INIT **********
    require_once("../src/init.php");


    if ($sesionIniciada) {
        if (isset($_POST['objeto'])) {
            if (isset($_POST['id'])) {
                switch ($_POST['objeto']) {
                    // ***** ELIMINACIÓN USERS (SOLO ADMIN O ID=ID) *****
                    case 'usuario':
                        if ($esAdmin || $_SESSION['id'] == $_GET['id']) {
                            //QUERY
                            //DELETE FROM

                            //SELECT FROM DE LA SIGUIENTE PAGINA
                            //Si son distintas hay que pillar el primer registro de la siguiente pagina +1
                            if ($paginaActual != $totalPaginas) {
                                //Comentarios por pagina a mostrar
                                $registrosPagina = DWESBaseDatos::REGISTROS_POR_PAGINA;

                                //Registro/comentario desde el que empezar a recorrer la tabla
                                $registroInicial = ($paginaActual-1)*$registrosPagina;
                                
                                $consulta = DWESBaseDatos::obtenListadoBuddies($db, $registroInicial+1);
                                echo ($consulta['nombre']);
                            }
                            
                        }else{
                            echo "error";
                            die();
                        }
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