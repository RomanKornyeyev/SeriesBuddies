//función que recibe el elemento y la url para la solicitud POST
function contarResultados(elemento, url) {

    //la consulta aun no se ha hecho, elemento cargando, se pinta "..."
    elemento.innerHTML = "... resultados &gt;";

    //nuevo objeto XMLHttpRequest
    var xhttp = new XMLHttpRequest();

    //se comprueba si el estado de la respuesta es 4 (indicando que la respuesta está completa)
    //y si el estado HTTP es 200 (indicando que la solicitud se ha completado correctamente)
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            // respuesta del handler, la pintamos en el elemento
            elemento.innerHTML = this.responseText+" resultados &gt;";
        }
    };

    //se abre una conexión POST al controlador
    xhttp.open("POST", "count_resultados_handler.php", true);

    //establecemos el tipo de contenido de la solicitud
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    //pasamos la URL
    xhttp.send(`url=${url}`);
}