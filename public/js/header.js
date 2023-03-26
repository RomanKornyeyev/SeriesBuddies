var toggle = document.getElementById("nav-toggle");
var headerContent = document.getElementById("header-content");

//al hacer click al trigger, se despliega el menú
toggle.addEventListener("click", function(){
    //añadir la clase con el height al header__content
    headerContent.classList.toggle("height-calc-100-header");

    //desabilitar scroll al desplegar el menú
    if (window.onscroll == null) {
        var x = window.scrollX;
        var y = window.scrollY;
        window.onscroll = function(){ window.scrollTo(x, y) };
    }else{
        window.onscroll = null;
    }
});

//si el menú sigue desplegado al superar el punto de ruptura, se pliega
window.addEventListener("resize", function(){
    if (window.innerWidth > 810) {
        if (headerContent.classList.contains("height-calc-100-header")) {
            headerContent.classList.remove("height-calc-100-header");
        }
        //por si acaso si el escroll sigue bloqueado al superar el punto de ruptura, lo desbloqueamos
        window.onscroll = null;
    }
});
