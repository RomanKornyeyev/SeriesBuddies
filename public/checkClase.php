<?php
require_once("../src/init.php");
use form\claseMain\TMDB;

$tmdb=new TMDB();
// echo $tmdb->getAPIUrl().'<br>';
// echo $tmdb->getToken().'<br>';
// echo ($tmdb->getAdultFilter())? 'true<br>':'false<br>';
// echo $tmdb->getLang().'<br>';

// $url = $tmdb->urlSerie('true beauty');
// $url = $tmdb->urlSeriesGeneros(18);
$url = $tmdb->urlListadoGeneros();
$response = $tmdb->peticionHTTP($url)['genres'];
// echo '<pre>';
// print_r($response);
// echo '</pre>';

//print_r(count($response['genres']));

//listado de series por genero
//listado de generos
//informacion de una serie

// $url = $tmdb->urlListadoGeneros();
// $listadoGeneros = $tmdb->peticionHTTP($url);
// // echo '<pre>';
// // print_r($listadoGeneros);
// // echo '</pre>';

// $serie=$tmdb->getSerie('mr. queen');
// // echo '<pre>';
// // var_dump($serie);
// // echo '</pre>';

// $url = $tmdb->urlSeriesGeneros(18);
// //echo $url;
// $response = $tmdb->getSeriesGenero($url);
// // echo '<pre>';
// // var_dump($response);
// // echo '</pre>';


// $series=$tmdb->getSeriesNombre('kingdom');
// echo '<pre>';
// print_r($series);
// echo '</pre>';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$serie['serieTitle']?></title>
</head>
<body>
<h1 class="title title--l text-align-center">GÉNEROS</h1>
    <div class="main__content">
        
        <?php foreach ($response as $key => $value) {
            $url = $tmdb->urlSeriesGeneros($response[$key]['id']); 
            $resultados = $tmdb->peticionHTTP($url);
            ?>
            <div class="box">
                <a href="./series.php?id=<?=$response[$key]['id']?>" class="box-body-wrapper">
                    <div class="box__body box__body--gender">
                        <div class="box-body__info">
                            <h2 class="title title-gender"><?=$response[$key]['name']?></h2>
                            <p class="text-white"><?=$resultados['total_results']?> resultados &gt;</p>
                        </div>
                    </div>
                </a>
            </div>
        <?php } ?>

    </div>
    <script>
        const options = {
            method: 'GET',
            headers: {
            accept: 'application/json',
            Authorization: 'Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiI0ZWQyODFjMmE4YzU2NmIxMTM1OGYxMzA3Yzc4ZWUyMSIsInN1YiI6IjY0NGQzNzliMmQzNzIxMjg4NjAxMDIwYyIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.dF1hwzUEcQB-Ad-r7vwD3bgCj4W3vctULyT1bHGxQoE'
            }
        };
        
        fetch('https://api.themoviedb.org/3/discover/tv?include_adult=false&include_null_first_air_dates=false&language=en-US&page=2&sort_by=popularity.desc&with_genres=18', options)
            .then(response => response.json())
            .then(response => console.log(response))
            .catch(err => console.error(err));
    </script>
</body>

</html>
