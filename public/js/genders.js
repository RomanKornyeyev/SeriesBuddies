//delay opcional (loader)
const delay = ms => new Promise(res => setTimeout(res, ms));


//función que pinta los resultados de cada género recibiendo un node list como parámetro
async function contarResultados(arreglo) {

    await delay(1000);

    //iteramos el array de elementos HTML
    arreglo.forEach(elemento => {
        
        //nuevo objeto XMLHttpRequest
        var xhttp = new XMLHttpRequest();

        //se comprueba si el estado de la respuesta es 4 (indicando que la respuesta está completa)
        //y si el estado HTTP es 200 (indicando que la solicitud se ha completado correctamente)
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                // respuesta del handler, la pintamos en el elemento
                elemento.innerHTML = this.responseText+" series &gt;";
            }
        };

        //se abre una conexión POST al controlador
        xhttp.open("POST", "count_resultados_handler.php", true);

        //establecemos el tipo de contenido de la solicitud
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

        //pasamos la URL
        xhttp.send(`url=${elemento.getAttribute('data-url')}`);
    });

}

//seleccionamos el array
const arreglo = document.querySelectorAll(".resultados");

//llamamos a la función
contarResultados(arreglo);