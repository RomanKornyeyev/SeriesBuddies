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