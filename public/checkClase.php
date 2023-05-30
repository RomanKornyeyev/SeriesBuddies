<?php
require_once("../src/init.php");
use clases\api_tmdb\TMDB;
$tmdb=new TMDB();

//---------------------------------PARA LAS FOTOS DE LAS SERIES POR ID--------------------------------
/*
$idSerie = 112888;
$url=$tmdb->urlSerieImg($idSerie);
$fotos = $tmdb->peticionHTTP($url)['backdrops'];
echo '<pre>';
print_r($fotos);
echo '</pre>';

foreach ($fotos as $key => $value) {
    $imagenes[$key]['file_path'] = 'https://image.tmdb.org/t/p/w1280'.$fotos[$key]['file_path'];
}
echo '<pre>';
print_r($imagenes);
echo '</pre>';

foreach ($fotos as $key => $value) {
    echo "<img src=".$imagenes[$key]['file_path'].">";
}
*/



//--------------------------------PARA LA PAGINACION DE LOS COMENTARIOS--------------------------------
/*
$idSerie = 112888;
$paginaActual = 2;
$registrosPagina = 5;

$registroInicial = ($paginaActual-1)*$registrosPagina;
echo "Registro inicial: $registroInicial<br>";

$comentarios = $db->ejecuta("SELECT u.nombre, id_post, contenido, DATE_FORMAT(fecha, '%d %b %Y', 'es-ES') as fecha, img FROM respuestas r INNER JOIN usuarios u ON u.id=r.id_usuario WHERE id_post=? ORDER BY fecha LIMIT ?, ?;", $idSerie, $registroInicial, $registrosPagina);
$comentarios = $db->obtenDatos();
$comentarios = $db->getRespuestasSerie($db, $idSerie, $registroInicial);

echo '<pre>';
print_r($comentarios);
echo '</pre>';

$totalRegistros = $db->ejecuta('SELECT COUNT(*) FROM respuestas where id_post = ?;', $idSerie);
$totalRegistros = $db->obtenElDato()['COUNT(*)'];
echo "Total de los registros: $totalRegistros<br>";

$totalPaginas   = ceil($totalRegistros / $registrosPagina);
echo "Total paginas: $totalPaginas<br>";
*/


//--------------------------------PARA LA PAGINA BUDDIES--------------------------------
/*
$listadoBuddies     = $db->ejecuta ("SELECT 
                                    DISTINCT(id_post), u.id, nombre, correo, img 
                                    FROM respuestas r 
                                    INNER JOIN usuarios u ON u.id=r.id_usuario 
                                    WHERE id_post=? 
                                    LIMIT 10;", $idSerie);
$listadoBuddies     = $db->obtenDatos();

$totalRespuestas    = $db->ejecuta("SELECT id_usuario, COUNT(id_usuario) FROM respuestas GROUP BY id_usuario;");
$totalRespuestas    = $db->obtenDatos();

$totalSeries        = $db->ejecuta("WITH t AS (
                                                SELECT id_post, id_usuario 
                                                FROM respuestas 
                                                GROUP BY id_usuario, id_post 
                                                ORDER BY id_usuario, id_post) 
                                    SELECT count(id_post)
                                    FROM t GROUP BY id_usuario;");
$totalSeries        = $db->obtenDatos();

foreach ($listadoBuddies as $key => $value) {
    if ($listadoBuddies[$key]['id'] == $totalRespuestas[$key]['id_usuario']) {
        $listadoBuddies[$key]['total_series']       = $totalSeries[$key]['COUNT(id_post)'];
        $listadoBuddies[$key]['total_respuestas']   = $totalRespuestas[$key]['COUNT(id_usuario)'];
        $listadoBuddies[$key]['total_buddies']      = random_int(1, 20);
        $listadoBuddies[$key]['total_chips']        = random_int(1, 20);
    }
}

echo '<pre>';
print_r($listadoBuddies);
echo '</pre>';
*/



/*
$idUsuario = 1;
$infoBuddie = $db->ejecuta("SELECT id, nombre, img, correo, descripcion FROM usuarios WHERE id=?;", $idUsuario);
$infoBuddie = $db->obtenDatos()[0];



$series = $db->ejecuta("SELECT DISTINCT(id_post) FROM respuestas WHERE id_usuario=?;", $idUsuario);
$series = $db->obtenDatos();
// array_walk($series, function ($key, $value) {
//     foreach ($key as $key => $value) {
//         echo $value." ";
//     }
// });
foreach ($series as $key => $value) {
    $series[$key] = $series[$key]['id_post'];
}
array_push($series[$key]);
echo '<pre>';
print_r($infoBuddie);
echo '</pre>';

echo '<pre>';
print_r($series);
echo '</pre>';
*/

// WITH series AS (SELECT DISTINCT(id_post)'id_serie', id_usuario FROM respuestas WHERE id_usuario=1) SELECT u.id, nombre, img, correo, descripcion, id_serie
// FROM series s, usuarios u WHERE s.id_usuario = u.id;





//-----------------------------------------------------------------------------------------------

// $serieID = 112888;
// $consulta = $db->ejecuta('SELECT u.nombre, id_post, contenido, fecha, img FROM respuestas r INNER JOIN usuarios u ON u.id=r.id_usuario WHERE id_post=? ORDER BY fecha LIMIT 5;', $serieID);
// $consulta = $db->obtenDatos();

// $totalPaginas = $db->ejecuta('SELECT COUNT(*) FROM respuestas where id_post = ?;', $serieID);
// $totalPaginas = $db->obtenElDato()['COUNT(*)'];
// print_r($totalPaginas);

//-----------------------------------------------------------------------------------------------



// echo $tmdb->getAPIUrl().'<br>';
// echo $tmdb->getToken().'<br>';
// echo ($tmdb->getAdultFilter())? 'true<br>':'false<br>';
// echo $tmdb->getLang().'<br>';

//$url = $tmdb->urlListadoGeneros();
// $url = $tmdb->urlSeriesGeneros(18);
// $url = $tmdb->urlSerie('true beauty');
// $response = $tmdb->peticionHTTP($url);
// echo '<pre>';
// print_r($response);
// echo '</pre>';

//print_r(count($response['genres']));

//listado de series por genero
//listado de generos
//informacion de una serie

// $url = $tmdb->urlListadoGeneros();
// $url = $tmdb->urlSeriesGeneros(99, 10);
// $listadoGeneros = $tmdb->peticionHTTP($url);
// echo '<pre>';
// print_r($listadoGeneros);
// echo '</pre>';

// $serie=$tmdb->getSerie('mr. queen');
// // echo '<pre>';
// // var_dump($serie);
// // echo '</pre>';

// //echo $url;
// $registrosPagina = 20;
// $totalPaginas = 500;

// if (isset($_GET['pagina'])) {
//     $paginaActual = $_GET['pagina'];
// } else {
//     $paginaActual = 1;
// }

// $response = $tmdb->getSeriesGenero(18, 1);
// echo '<pre>';
// var_dump($response);
// echo '</pre>';
// for ($i=1; $i <= $totalPaginas; $i++) {
//     echo '<a href="checkClase.php?pagina='.$i.'">'.$i.'</a> ';
// }

//$series=$tmdb->getSeriesNombre('simpson');
// $urlSerie =$tmdb->urlSerieNombre('simpson');
// $series=$tmdb->peticionHTTP($urlSerie)['results'];
// echo '<pre>';
// print_r($series);
// echo '</pre>';

// $response=$tmdb->getSerieID(112888);
// echo '<pre>';
// print_r($response);
// echo '</pre>';


/* ---------------------------------------------- RESULTADOS DE BUDDIES POR BUSCADOR ---------------------------------------------- */

$busqueda = $_GET['busqueda'];
if (isset($_GET['pagina'])) {
    $paginaActual = $_GET['pagina'];
} else {
    $paginaActual = 1;
}

//Comentarios por pagina a mostrar
$registrosPagina = DWESBaseDatos::REGISTROS_POR_PAGINA;
//Registro/comentario desde el que empezar a recorrer la tabla
$registroInicial = ($paginaActual-1)*$registrosPagina;

$buddiesEncontrados = DWESBaseDatos::obtenListadoBuddiesBusqueda($db, $busqueda, $registroInicial);
echo '<pre>';
print_r($buddiesEncontrados);
echo '</pre>';