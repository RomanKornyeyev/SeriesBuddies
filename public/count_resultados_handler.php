<?php

    // ***** INIT *****
    require_once("../src/init.php");

    // ***** API TMDB *****
    use clases\api_tmdb\TMDB;

    //instanciamos un nuevo objeto de la clase de la API
    $tmdb = new TMDB();

    //la url que se pasa por POST viene dividida por cachos
    //la reestructuramos para dejarla como un string entero
    $url = "";
    $i = 0;
    foreach($_POST as $key => $value){
        if($i == 0){
            $url = $value;
        }else if($i == 1){
            $url .= "?".$key."=".$value;
        }else{
            $url .= "&".$key."=".$value;
        }
        $i++;
    }

    //hacemos la petición e imprimimos los resultados
    $resultados = $tmdb->peticionHTTP($url);
    echo $resultados['total_results'];