// obtén los elementos
const contenedor = document.getElementById('hero');
const elemento1 = document.getElementById('row-top');
const elemento2 = document.getElementById('row-bottom');

// agrega un manejador de eventos para el movimiento del ratón en el la ventana
window.addEventListener('mousemove', function(event) {
  //pos ratón en el cliente
  const mouseX = event.clientX;
  const mouseY = event.clientY;  

  // actualiza la posición del contenido en relación a la posición del ratón
  elemento1.style.transform = `translate(-${mouseX * 0.05}px, -${mouseY * 0.05}px)`;
  elemento2.style.transform = `translate(${mouseX * 0.05}px, ${mouseY * 0.05}px)`;
  contenedor.style.transform = `translate(-${mouseX * 0.025}px, -${mouseY * 0.025}px)`;
});