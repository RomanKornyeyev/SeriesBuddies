<!DOCTYPE html>
<html lang="es-ES">
<head>
    <!-- META -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="La mejor moda friki de la web">
    <meta name="keywords" content="camisetas, accesorios, desarrollo web, programación">
    <meta name="author" content="Anabel, Franco y Román">
    <meta name="copyright" content="Anabel, Franco y Román">
    
    <!-- CSS -->
    <link rel="stylesheet" href="css/general.css">
    
    <!-- TITLE -->
    <title>BOTONSITOS</title>
    <link rel="icon" type="image/png" href="img/icon.png">

    <!-- CSS INTERNO (XD) -->
    <style>
        body{
            background-color: var(--background-800);
        }
        .container{
            width: 100%;
            max-width: 120rem;
            margin: 0 auto;
            background-color: var(--background-700);
            color: #eaeaea;
            border: none;
        }
        .section{
            display: flex;
            flex-flow: row wrap;
            gap: 2.5rem;
            padding: 2.5rem;
            border-bottom: 1px solid#eaeaea;
        }
        .section:last-child{
            border: none;
        }
        .section__title{
            width: 100%;
        }
        .section__buttons{
            display: flex;
            flex-flow: column;
            justify-content: space-between;
            gap: 2.5rem;
        }
        .button-desc{
            font-family: 'Courier New', Courier, monospace !important;
        }
    </style>
</head>
<body>
    <div class="container">
        <section class="section">
            <h2 class="section__title">BOTÓN PRIMARIO</h2>
            <div class="section__buttons">
                <div class="button"><a href="#categories" class="btn btn--primary btn--sm">COMPRAR</a></div><br>
                <div class="button-desc"><strong>PEQUEÑO<br></strong>btn btn--sm<br>btn--primary</div>
            </div>
            <div class="section__buttons">
                <div class="button"><a href="#categories" class="btn btn--primary">COMPRAR</a></div><br>
                <div class="button-desc"><strong>NORMAL<br></strong>btn<br>btn--primary</div>
            </div>
            <div class="section__buttons">
                <div class="button"><a href="#categories" class="btn btn--primary btn--md">COMPRAR</a></div><br>
                <div class="button-desc"><strong>MEDIO<br></strong>btn btn--md<br>btn--primary</div>
            </div>
            <div class="section__buttons">
                <div class="button"><a href="#categories" class="btn btn--primary btn--lg">COMPRAR</a></div><br>
                <div class="button-desc"><strong>GRANDE<br></strong>btn btn--lg<br>btn--primary</div>
            </div>
        </section>


        <section class="section">
            <h2 class="section__title">BOTÓN SECUNDARIO</h2>
            <div class="section__buttons">
                <div class="button"><a href="#" class="btn btn--sm btn--secondary">AÑADIR</a></div><br>
                <div class="button-desc"><strong>PEQUEÑO<br></strong>btn btn--sm<br>btn--secondary</div>
            </div>
            <div class="section__buttons">
                <div class="button"><a href="#" class="btn btn--secondary">AÑADIR</a></div><br>
                <div class="button-desc"><strong>NORMAL<br></strong>btn<br>btn--secondary</div>
            </div>
            <div class="section__buttons">
                <div class="button"><a href="#" class="btn btn--md btn--secondary">AÑADIR</a></div><br>
                <div class="button-desc"><strong>MEDIO<br></strong>btn btn--md<br>btn--secondary</div>
            </div>
            <div class="section__buttons">
                <div class="button"><a href="#" class="btn btn--lg btn--secondary">AÑADIR</a></div><br>
                <div class="button-desc"><strong>GRANDE<br></strong>btn btn--lg<br>btn--secondary</div>
            </div>
        </section>


        
        <section class="section">
            <h2 class="section__title">BOTÓN OUTLINE</h2>
            <div class="section__buttons">
                <div class="button"><a href="#" class="btn btn--sm btn--outline">AÑADIR</a></div><br>
                <div class="button-desc"><strong>PEQUEÑO<br></strong>btn btn--sm<br>btn--outline</div>
            </div>
            <div class="section__buttons">
                <div class="button"><a href="#" class="btn btn--outline">AÑADIR</a></div><br>
                <div class="button-desc"><strong>NORMAL<br></strong>btn<br>btn--outline</div>
            </div>
            <div class="section__buttons">
                <div class="button"><a href="#" class="btn btn--md btn--outline">AÑADIR</a></div><br>
                <div class="button-desc"><strong>MEDIO<br></strong>btn btn--md<br>btn--outline</div>
            </div>
            <div class="section__buttons">
                <div class="button"><a href="#" class="btn btn--lg btn--outline">AÑADIR</a></div><br>
                <div class="button-desc"><strong>GRANDE<br></strong>btn btn--lg<br>btn--outline</div>
            </div>
        </section>
    </div>
</body>
</html>