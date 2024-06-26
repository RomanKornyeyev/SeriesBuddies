<?php

    // ***** INIT *****
    require_once("../src/init.php");

    // ***** PETICIONES *****
    use clases\peticiones\Peticion;

    $peticionFooter = new Peticion($esAdmin);

    if ($sesionIniciada) {
        if (isset($_POST['accion'])) {
            switch (strval($_POST['accion'])) {
                //envio petición amistad
                case 'enviar':
                    $db->ejecuta(
                        "INSERT INTO peticiones (id_emisor, id_receptor, estado) VALUES (?, ?, ?);",
                        $_SESSION["id"], $_POST["id"], Peticion::ESTADO_PENDIENTE
                    );
                    echo $peticionFooter->pintaAmistadEnviada($_POST["id"], $_POST["pagina_actual"], $_POST["total_paginas"], $_POST['tipo']);
                    break;

                //cancelar petición
                case 'cancelar':
                    $db->ejecuta(
                        "DELETE FROM peticiones WHERE id_receptor=? AND id_emisor=? AND estado=?;",
                        $_POST['id'], $_SESSION['id'], Peticion::ESTADO_PENDIENTE
                    );
                    echo $peticionFooter->pintaAmistadNula($_POST["id"], $_POST["pagina_actual"], $_POST["total_paginas"], $_POST['tipo']);
                    break;

                //aceptar petición
                case 'aceptar':
                    $db->ejecuta(
                        "UPDATE peticiones SET estado=? WHERE id_receptor=? AND id_emisor=?;",
                        Peticion::ESTADO_ACEPTADO, $_SESSION['id'], $_POST['id']
                    );
                    $db->ejecuta(
                        "INSERT INTO peticiones (id_receptor, id_emisor, estado) VALUES (?,?,?);",
                        $_POST['id'], $_SESSION['id'], Peticion::ESTADO_ACEPTADO
                    );
                    if($_POST['tipo'] != Peticion::NOTIFICACION_PROFILE){
                        echo $peticionFooter->pintaAmistadMutua($_POST["id"], $_POST["pagina_actual"], $_POST["total_paginas"], $_POST['tipo']);
                    }
                    break;

                //rechazar petición
                case 'rechazar':
                    $db->ejecuta(
                        "DELETE FROM peticiones WHERE id_receptor=? AND id_emisor=?;",
                        $_SESSION['id'], $_POST['id']
                    );
                    if($_POST['tipo'] != Peticion::NOTIFICACION_PROFILE){
                        echo $peticionFooter->pintaAmistadNula($_POST["id"], $_POST["pagina_actual"], $_POST["total_paginas"], $_POST['tipo']);
                    }
                    break;

                //eliminar amigo
                case 'eliminar':
                    $db->ejecuta(
                        "DELETE FROM peticiones WHERE id_receptor=? AND id_emisor=?;",
                        $_SESSION['id'], $_POST['id']
                    );
                    $db->ejecuta(
                        "DELETE FROM peticiones WHERE id_receptor=? AND id_emisor=?;",
                        $_POST['id'], $_SESSION['id']
                    );
                    echo $peticionFooter->pintaAmistadNula($_POST["id"], $_POST["pagina_actual"], $_POST["total_paginas"], $_POST['tipo']);
                    break;

                default:
                    echo "error. REDIRIGIENDO...";
                    break;
            }

        // --- NO HAY ACCIÓN EN EL POST ---
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