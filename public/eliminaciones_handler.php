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
                            echo "borrado con éxito!";
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