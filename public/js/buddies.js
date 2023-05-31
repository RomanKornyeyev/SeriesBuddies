//baja la barra de opciones de la tarjeta del user
function bajar(elemento){
    let abuelo = elemento.parentNode.parentNode;
    abuelo.classList.add("bajar");
}

//sube la barra de opciones de la tarjeta del user
function subir(elemento){
    let abuelo = elemento.parentNode.parentNode;
    abuelo.classList.remove("bajar");
}

//deshabilitar clicks de todos los elementos hermanos
function disableSiblingClicks(element) {
    let parent = element.parentNode;
    let siblings = Array.from(parent.children);
  
    siblings.forEach(function(sibling) {
      if (sibling !== element) {
        sibling.onclick = function(event) {
          event.stopPropagation();
          event.preventDefault();
        };
      }
    });
  }


function peticion(elemento, id, accion, paginaActual=1, totalPaginas=1, tipo){
    console.log(`
        elemento: ${elemento}
        id: ${id}
        accion: ${accion}
        paginaActual: ${paginaActual}
        totalPaginas: ${totalPaginas}
        tipo: ${tipo}
    `);

    let globalElementOverwrite = elemento.parentNode.parentNode.parentNode;

    elemento.className = "";
    elemento.onclick = null;
    elemento.innerHTML = "<i class='fa-solid fa-spinner rotate-infinite font-size-default'></i>";
    disableSiblingClicks(elemento);
    if (accion === "enviar") {
        elemento.classList.add("btn-no-clickable-primary");
    }else if (accion === "aceptar"){
        elemento.classList.add("btn-no-clickable-success");
    }else{
        elemento.classList.add("btn-no-clickable-error");
    }
    
    console.log("paso 1 (antes del onreadystatechange)");

    //nuevo objeto XMLHttpRequest
    var xhttp = new XMLHttpRequest();

    //cuando hay un cambio de estado
    xhttp.onreadystatechange = async function() {
        console.log("paso 2 (dentro del onreadystatechange). STATE: "+this.readyState);

        //si todo está oki, 4 (respuesta está completa) y 200 (solicitud HTTP correcta)
        if (this.readyState == 4) {
            await delay(1000);
            if (this.status == 200) {
                // respuesta del handler, la pintamos en el elemento
                elemento.parentNode.innerHTML = "<div class='btn-no-clickable-success pos-absolute opacity-fade'><i class='fa-solid fa-check'></i></div>";
                await delay(1000);
                console.log("paso 3 (dentro del if del onreadystatechange)");
                globalElementOverwrite.innerHTML = this.responseText;
            //¿Error en el handler? Pintamos un error
            }else if (this.status == 500 || this.status == 400){
                elemento.parentNode.innerHTML = "<div class='btn-no-clickable-error pos-absolute'>ERROR&nbsp;<i class='fa-solid fa-xmark'></i></div>";
            }
        }
    };

    //se abre una conexión POST al controlador
    xhttp.open("POST", "peticiones_handler.php", true);

    //establecemos el tipo de contenido de la solicitud
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    //pasamos la petición POST
    xhttp.send(`accion=${accion}&id=${id}&pagina_actual=${paginaActual}&total_paginas=${totalPaginas}&tipo=${tipo}`);

}

function peticionNotificacion(elemento, id, accion, tipo=3){
    console.log(`
        elemento: ${elemento}
        id: ${id}
        accion: ${accion}
        tipo: ${tipo}
    `);

    let footer = elemento.parentNode.parentNode.parentNode;
    console.log(footer);
    let cardGlobal = elemento.parentNode.parentNode.parentNode.parentNode;
    console.log(cardGlobal)

    elemento.className = "";
    elemento.onclick = null;
    elemento.innerHTML = "<i class='fa-solid fa-spinner rotate-infinite font-size-default'></i>";
    disableSiblingClicks(elemento);
    
    if (accion === "aceptar"){
        elemento.classList.add("btn-no-clickable-success");
    }else{
        elemento.classList.add("btn-no-clickable-error");
    }

    //nuevo objeto XMLHttpRequest
    var xhttp = new XMLHttpRequest();

    //cuando hay un cambio de estado
    xhttp.onreadystatechange = async function() {
        //si todo está oki, 4 (respuesta está completa) y 200 (solicitud HTTP correcta)
        if (this.readyState == 4) {
            await delay(1000);
            if (this.status == 200) {
                // respuesta del handler, la pintamos en el elemento
                if (accion === "aceptar"){
                    elemento.parentNode.innerHTML = "<div class='btn-no-clickable-success pos-absolute opacity-fade'><i class='fa-solid fa-check'></i></div>";
                } else {
                    elemento.parentNode.innerHTML = "<div class='btn-no-clickable-error pos-absolute opacity-fade'><i class='fa-solid fa-xmark'></i></div>";
                }
                
                cardGlobal.classList.add("opacity-fade");
                await delay(1000);
                cardGlobal.remove();
            //¿Error en el handler? Pintamos un error
            }else if (this.status == 500 || this.status == 400){
                elemento.parentNode.innerHTML = "<div class='btn-no-clickable-error pos-absolute'>ERROR&nbsp;<i class='fa-solid fa-xmark'></i></div>";
            }
        }
    };

    //se abre una conexión POST al controlador
    xhttp.open("POST", "peticiones_handler.php", true);

    //establecemos el tipo de contenido de la solicitud
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    //pasamos la petición POST
    xhttp.send(`accion=${accion}&id=${id}&tipo=${tipo}`);

}


function eliminar(elemento, id, paginaActual, totalPaginas){
    let objeto = 'usuario';
    //confirmación
    // confirmacion();
    mostrarConfirmacion()
        .then(async function(resultado) {
            // El usuario ha confirmado
            if (resultado) {
                //tarjeta user
                let globalElementOverwrite = elemento.parentNode.parentNode.parentNode.parentNode;
                let mainGlobal = globalElementOverwrite.parentNode;
                let div = document.createElement("div");
                div.className = "card-dark-loader opacity-fade-in-short";
                div.innerHTML = "<i class='fa-solid fa-spinner rotate-infinite'></i>";
                globalElementOverwrite.insertAdjacentElement("beforeend", div);
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
                            globalElementOverwrite.remove();
                            mainGlobal.innerHTML += this.responseText;

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
                xhttp.send(`objeto=${objeto}&id=${id}&pagina_actual=${paginaActual}&total_paginas=${totalPaginas}`);


                console.log("si");
            // El usuario ha cancelado
            } else {
                
                
                console.log("no");
            }
        });
}

/* --------------------------------------------- JS PARA LAS GALERIAS DE LOS PROFILES --------------------------------------------- */
function App () {}
    window.onload = function(event) {
        var app = new App();
        window.app = app;
    }

    App.prototype.processingButton = function(event) {
        const btn = event.currentTarget;
        const list = event.currentTarget.parentNode;
        const gallery = event.currentTarget.parentNode.querySelector('#gallery');
        const carrusel = gallery.querySelectorAll('.carrusel');

        const carruselWidth = carrusel[0].offsetWidth;
        const galleryWidth = gallery.offsetWidth;
        const listWidth =  list.offsetWidth;

        gallery.style.left == "" ? leftPosition = gallery.style.left = 0 : leftPosition = parseFloat(gallery.style.left.slice(0,-2) * -1);
        btn.dataset.button == 'button-prev' ? prevAction(leftPosition, carruselWidth, gallery) : nextAction(leftPosition, galleryWidth, listWidth, carruselWidth, gallery);

    }

    let prevAction = (leftPosition, carruselWidth, gallery) => {
        if (leftPosition > 0) {
            gallery.style.left = `${-1*(leftPosition - carruselWidth)}px`;
        }
    }

    let nextAction = (leftPosition, galleryWidth, listWidth, carruselWidth, gallery) => {
        if (leftPosition < (galleryWidth - listWidth)) {
            gallery.style.left = `${-1*(leftPosition + carruselWidth)}px`;
        }
    }