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