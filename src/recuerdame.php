<?php

    //si el usuario NO tiene la sesión iniciada
    if (!isset($_SESSION['nombre'])) {
        //comprobamos si el user tiene la cookie de recuerdame
        if(isset($_COOKIE['recuerdame'])){
            //si la tiene, cogemos el valor y comprobamos a ver si coincide con alguno de la base de datos
            $tkn = $_COOKIE['recuerdame'];
            $db->ejecuta(
                "SELECT * FROM tokens WHERE valor=?;",
                $tkn
            );
            $consulta = $db->obtenElDato();

            //si el token es válido y no está expirado
            if ($consulta != "" && $consulta['expiracion'] > date('Y-m-d H:i:s')) {
                
                //buscamos el user
                $db->ejecuta(
                    "SELECT * FROM usuarios WHERE id=(SELECT id_usuario FROM tokens WHERE valor=?);",
                    $tkn
                );
                $consulta = $db->obtenElDato();

                //si la consulta devuelve un user, es que la cookie es buena, y hacemos $_SESSION de nombre
                if ($consulta != ""){
                    //nos traemos todos los datos del user
                    $_SESSION['id'] = $consulta['id'];
                    $_SESSION['nombre'] = $consulta['nombre'];
                    $_SESSION['contra'] = $consulta['contra'];
                    $_SESSION['img'] = $consulta['img'];
                    $_SESSION['correo'] = $consulta['correo'];
                    $_SESSION['privilegio'] = $consulta['privilegio'];


                    //alargamos la vida del token (7 días)
                    $db->ejecuta(
                        "UPDATE tokens SET expiracion=(NOW() + INTERVAL 7 DAY) WHERE valor=?",
                        $tkn
                    );
                }
            }
        }
    }

?>