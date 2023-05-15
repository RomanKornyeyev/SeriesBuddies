<?php
namespace form\claseMain;
class TMDB
{
    //$apiKey = '4ed281c2a8c566b11358f1307c78ee21'; 
    const API_URL = 'https://api.themoviedb.org/3/';
    public $token = 'Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiI0ZWQyODFjMmE4YzU2NmIxMTM1OGYxMzA3Yzc4ZWUyMSIsInN1YiI6IjY0NGQzNzliMmQzNzIxMjg4NjAxMDIwYyIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.dF1hwzUEcQB-Ad-r7vwD3bgCj4W3vctULyT1bHGxQoE';
    public $adult = false;
    public $lang = 'es-ES';
    public $action = 'discover/tv?';
    
    public function setAdultFilter ($adult) {$this->adult = $adult;}    
    public function setLang ($lang) {$this->lang = $lang;}

    public function getAPIUrl () {return self::API_URL;}
    public function getToken () {return $this->token;}
    public function getAdultFilter () {return $this->adult;}
    public function getLang () {return $this->lang;}

    public function peticionHTTP ($url) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_ENCODING, '');
        curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            "Authorization: ".$this->token,
            "accept: application/json"
        ]);
        
        $response = curl_exec($curl);
        curl_close($curl);
        return (array) json_decode(($response), true);
    }
    
    public function urlSeriesGeneros ($generosID) {
        return self::API_URL.'discover/tv?include_adult='.$this->adult.'&include_null_first_air_dates=false&language='.$this->lang.'&page=1&sort_by=popularity.desc&with_genres='.$generosID;
    }

    public function urlSerie($nbSerie) {
        $nbSerie = str_replace(' ', '%20', $nbSerie);
        return self::API_URL.'search/tv?query='.$nbSerie.'&include_adult='.$this->adult.'&language='.$this->lang;
    }

    public function urlListadoGeneros () {
        return self::API_URL.'genre/tv/list?language='.$this->lang;
    }

    public function urlSerieImg ($codigo) {
        return self::API_URL.'tv/'.$codigo.'/images';
    }

    public function getNbGeneros ($listado, $generos) {
        foreach ($listado as $listadoGeneros) {
            foreach ($listadoGeneros as $nbGeneros) {
                foreach ($generos as $kg) {
                    if ($kg == $nbGeneros) {
                        $generosSerie[$kg] = $listadoGeneros['name'];
                    }
                }
            }
        }
        return $generosSerie;
    }

    public function getSeriesNombre ($nombre) {
        $urlSerie =$this->urlSerie($nombre);
        $series=$this->peticionHTTP($urlSerie)['results'];
        $baseUrlImage='https://image.tmdb.org/t/p/';
        $imageSize='w300';

        foreach ($series as $key => $serie) {
            foreach ($serie as $clave => $contenido) {
                if ($clave=='name' && strcasecmp($contenido, $nombre) == 0) {
                    $backdropPath                     =  $series[$key]['backdrop_path'];
                    $imagePath                        =  $series[$key]['poster_path'];

                    $serieData[$key]['serieTitle']    =  $series[$key]['name'];
                    $serieData[$key]['posterImage']   =  $baseUrlImage.$imageSize.$imagePath;
                    $serieData[$key]['backdrop']      =  $baseUrlImage.$imageSize.$backdropPath;
                    $serieData[$key]['seriePlot']     =  $series[$key]['overview'];
                    $serieData[$key]['serieGenres']   =  $series[$key]['genre_ids'];
                    $serieData[$key]['serieAirDate']  =  $series[$key]['first_air_date'];
                }
            }
        }

        $serieData = $this->mapearGenerosID($serieData);
        return $serieData;
    }

    public function getSeriesGenero ($generoID) {
        $urlSerie =$this->urlSeriesGeneros($generoID);
        $series=$this->peticionHTTP($urlSerie)['results'];
        $baseUrlImage='https://image.tmdb.org/t/p/';
        $imageSize='w300';

        foreach ($series as $key => $serie) {
            $backdropPath                     =  $series[$key]['backdrop_path'];
            $imagePath                        =  $series[$key]['poster_path'];

            $serieData[$key]['serieTitle']    =  $series[$key]['name'];
            $serieData[$key]['posterImage']   =  $baseUrlImage.$imageSize.$imagePath;
            $serieData[$key]['backdrop']      =  $baseUrlImage.$imageSize.$backdropPath;
            $serieData[$key]['seriePlot']     =  $series[$key]['overview'];
            $serieData[$key]['serieGenres']   =  $series[$key]['genre_ids'];
            $serieData[$key]['serieAirDate']  =  $series[$key]['first_air_date'];
        }

        $serieData = $this->mapearGenerosID($serieData);
        return $serieData;
    }

    public function mapearGenerosID ($serieData) {
        $urlGeneros = $this->urlListadoGeneros();
        $listadoGeneros = $this->peticionHTTP($urlGeneros)['genres'];

        foreach ($serieData as $key => $serie) {
            foreach ($serie as $clave => $value) {
                if ($clave == 'serieGenres') {
                    $serieData[$key]['serieGenres'] = $this->getNbGeneros ($listadoGeneros, $value);
                }
            }
        }

        return $serieData;
    }

}
?>
