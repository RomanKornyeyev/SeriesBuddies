<?php

namespace clases\api_tmdb;

class TMDB
{
    //VARIABLES Y CONSTANTES
    //const API_KEY = '4ed281c2a8c566b11358f1307c78ee21'; 
    const API_URL = 'https://api.themoviedb.org/3/';
    const TOKEN = 'Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiI0ZWQyODFjMmE4YzU2NmIxMTM1OGYxMzA3Yzc4ZWUyMSIsInN1YiI6IjY0NGQzNzliMmQzNzIxMjg4NjAxMDIwYyIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.dF1hwzUEcQB-Ad-r7vwD3bgCj4W3vctULyT1bHGxQoE';
    const MAX_PAGINAS = 500;
    const BASE_URL_IMG='https://image.tmdb.org/t/p/';
    const WIDTH_BACKDROP = 'w1280';
    const WIDTH_POSTER = 'w780';
    public $adult = false;
    public $lang = 'es-ES';

    //GETTERS Y SETTERS
    public function setAdultFilter ($adult) {$this->adult = $adult;}    
    public function setLang ($lang) {$this->lang = $lang;}

    public function getAPIUrl () {return self::API_URL;}
    public function getToken () {return self::TOKEN;}
    public function getAdultFilter () {return $this->adult;}
    public function getLang () {return $this->lang;}

    //Lanza la peticion a la api con la url especificada y lo devuelve como un array
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
            "Authorization: ".self::TOKEN,
            "accept: application/json"
        ]);
        
        $response = curl_exec($curl);
        curl_close($curl);
        return (array) json_decode(($response), true);
    }
    
    //Devuelve la url para buscar la info de la serie por su ID.
    public function urlSerieID ($serieID) {
        return self::API_URL.'tv/'.$serieID.'?language='.$this->lang;
    }

    //Devuelve la url para buscar las series que pertencen a un genero (por su ID) en determinada pagina (por defecto es la primera)
    public function urlSeriesGeneros ($generosID, $currentPage=1) {
        return self::API_URL.'discover/tv?include_adult='.$this->adult.'&include_null_first_air_dates=false&language='.$this->lang.'&page='.$currentPage.'&sort_by=popularity.desc&with_genres='.$generosID;
    }

    //Devuelve la url para sacar el listado completo de los generos de las series de tv
    public function urlListadoGeneros () {
        return self::API_URL.'genre/tv/list?language='.$this->lang;
    }

    //Devuelva la info de una serie a partir de su ID
    public function getSerieID ($serieID) {
        $urlSerie = $this->urlSerieID($serieID);
        $serie = $this->peticionHTTP($urlSerie);
        
        $serieData['serieTitle']    =  $serie['name'];

        //Si no hay foto banner para la serie se pone el logo de seriesbuddies por defecto
        if ($serie['backdrop_path'] == '') {
            $serieData['backdrop']  =  'upload/logos/logo-poster.png';
        } else {
            $serieData['backdrop']  =  self::BASE_URL_IMG.self::WIDTH_BACKDROP.$serie['backdrop_path'];
        }
        
        //Si no hay descripcion para la serie se pone el texto por defecto
        if ($serie['overview'] == '') {
            $serieData['seriePlot'] =  'Lo sentimos, pero en estos momentos no contamos con la trama de esta serie. Estamos trabajando para disponer de ella en la máxima brevedad posible.';
        } else {
            $serieData['seriePlot'] =  $serie['overview'];
        }
        $serieData['serieAirDate']  =  $serie['first_air_date'];
        $serieData['serieGenres']   =  $serie['genres'];

        return $serieData;
    }

    //Devuelve el nombre de los generos recibiendo el listado de los generos (id+nb) y el listado a buscar (solo id)
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
    
    //Devuelve la info de todas las series que pertenecen a un genero pero por paginas especificas.
    public function getSeriesGenero ($generoID, $currentPage=1) {
        $urlSerie =$this->urlSeriesGeneros($generoID, $currentPage);
        $resultados=$this->peticionHTTP($urlSerie);
        $series = $resultados['results'];
        //Guardamos el total de paginas, si sobrepasan las 500 permitidas almacenamos ese maximo
        $totalPages = ($resultados['total_pages'] <= self::MAX_PAGINAS)? $resultados['total_pages']: $totalPages = self::MAX_PAGINAS;

        foreach ($series as $key => $serie) {
            $imagePath                        =  $series[$key]['poster_path'];
            $serieData[$key]['id']            =  $series[$key]['id'];
            $serieData[$key]['serieTitle']    =  $series[$key]['name'];
            
            //Si no hay foto banner para la serie se pone el logo de seriesbuddies por defecto
            if ($series[$key]['poster_path'] == '') {
                $serieData[$key]['posterImage'] = 'upload/logos/logo-poster.png';
            } else {
                $serieData[$key]['posterImage']   =  self::BASE_URL_IMG.self::WIDTH_POSTER.$imagePath;
            }
            
            //Si no hay descripcion para la serie se pone el texto por defecto
            if ($series[$key]['overview'] == '') {
                $serieData[$key]['seriePlot'] =  'Lo sentimos, pero en estos momentos no contamos con la trama de esta serie. Estamos trabajando para disponer de ella en la máxima brevedad posible.';
            } else {
                $serieData[$key]['seriePlot'] =  $series[$key]['overview'];
            }

            $serieData[$key]['serieGenres']   =  $series[$key]['genre_ids'];
            $serieData[$key]['serieAirDate']  =  $series[$key]['first_air_date'];
        }

        $serieData = $this->mapearGenerosID($serieData);
        array_push($serieData, $totalPages);
        return $serieData;
    }

    //Mapea los ids de los generos con sus nombres cuando lo que recibes es un array
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







    //********** LEGACY CODE **********
    //Devuelve la url para buscar todas las imagenes sobre una serie determinada dado su ID
    public function urlSerieImg ($codigo) {
        return self::API_URL.'tv/'.$codigo.'/images';
    }

    //Devuelve la url para la info de la serie por su nombre
    public function urlSerieNombre($nbSerie) {
        $nbSerie = str_replace(' ', '%20', $nbSerie);
        return self::API_URL.'search/tv?query='.$nbSerie.'&include_adult='.$this->adult.'&language='.$this->lang;
    }
    
    //Devuelve la info de las series cuyo nombre coincide
    public function getSeriesNombre ($nombre) {
        $urlSerie =$this->urlSerieNombre($nombre);
        $series=$this->peticionHTTP($urlSerie)['results'];

        foreach ($series as $key => $serie) {
            foreach ($serie as $clave => $contenido) {
                if ($clave=='name' && strcasecmp($contenido, $nombre) == 0) {
                    $backdropPath                     =  $series[$key]['backdrop_path'];
                    $imagePath                        =  $series[$key]['poster_path'];

                    $serieData[$key]['serieTitle']    =  $series[$key]['name'];
                    $serieData[$key]['posterImage']   =  self::BASE_URL_IMG.self::WIDTH_POSTER.$imagePath;
                    $serieData[$key]['backdrop']      =  self::BASE_URL_IMG.self::WIDTH_BACKDROP.$backdropPath;
                    $serieData[$key]['seriePlot']     =  $series[$key]['overview'];
                    $serieData[$key]['serieGenres']   =  $series[$key]['genre_ids'];
                    $serieData[$key]['serieAirDate']  =  $series[$key]['first_air_date'];
                }
            }
        }

        $serieData = $this->mapearGenerosID($serieData);
        return $serieData;
    }
}
?>
