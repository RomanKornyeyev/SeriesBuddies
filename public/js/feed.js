window.addEventListener('DOMContentLoaded', function() {
    let cuadroRespuesta = document.getElementById('respuesta');
    
    if (cuadroRespuesta !== null) {
        cuadroRespuesta.scrollIntoView({ behavior: 'smooth', block: 'center', inline: 'nearest' });
    }
});

var urlActual = window.location.href;
console.log(urlActual);
function eliminar(elemento, id, idSerie, paginaActual, totalPaginas){
    //objeto a ser borrado (usuario o respuesta)
    let objeto = 'respuesta';

    //confirmación
    mostrarConfirmacion()
        .then(async function(resultado) {
            // El usuario ha confirmado
            if (resultado) {
                //tarjeta user
                let respuesta = elemento.parentNode.parentNode.parentNode.parentNode.parentNode;
                let respuestaShadow = elemento.parentNode.parentNode.parentNode.parentNode;
                respuestaShadow.style.boxShadow = "none";
                let respuestasGlobal = document.getElementById("respuestas");

                //lo ponemos a cargar
                let div = document.createElement("div");
                div.className = "card-dark-loader opacity-fade-in-short";
                div.innerHTML = "<i class='fa-solid fa-spinner rotate-infinite'></i>";
                respuesta.insertAdjacentElement("beforeend", div);

                //esperamos 1,5sec
                await delay(1500);           

                //nuevo objeto XMLHttpRequest
                var xhttp = new XMLHttpRequest();

                //cuando hay un cambio de estado
                xhttp.onreadystatechange = async function() {
                    //si todo está oki, 4 (respuesta está completa) y 200 (solicitud HTTP correcta)
                    if (this.readyState == 4) {
                        //await delay(1000);
                        if (this.status == 200) {
                            // respuesta del handler, la pintamos en el elemento
                            respuesta.remove();
                            respuestasGlobal.innerHTML += this.responseText;

                        //¿Error en el handler? Pintamos un error
                        }else if (this.status == 500 || this.status == 400){
                            console.log('ERROR');
                        }
                    }
                }

                //se abre una conexión POST al controlador
                xhttp.open("POST", "eliminaciones_handler.php", true);
                
                //establecemos el tipo de contenido de la solicitud
                xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                
                //pasamos la petición POST
                xhttp.send(`objeto=${objeto}&id=${id}&id_serie=${idSerie}&pagina_actual=${paginaActual}&total_paginas=${totalPaginas}`);


                console.log("si");
            // El usuario ha cancelado
            } else {
                
                
                console.log("no");
            }
        });
}