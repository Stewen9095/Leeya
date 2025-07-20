<?php
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leeya</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="img/icon.png" type="image/png">
    <style>
        html { font-size: 15px; }

        body { margin:0; font-family: 'Montserrat'; background:#000; }
        header { background: transparent; box-shadow: none; }
        
        nav {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: clamp(1rem, 3vw, 2.5rem);
            width: 75vw;
            max-width: 75vw;
            min-width: 18rem;
            margin-left: auto;
            margin-right: auto;
            padding: 2rem 0rem 2.8rem 0rem;
            font-family: 'HovesExpandedBold';
            box-sizing: border-box;
        }
        .iconoimg {
            height: 3.5rem;
            width: auto;
            padding-bottom: 0.5rem;
        }
        .nav-btns {
            display: flex;
            gap: 0.8rem;
            align-items: center;
            background: #000080;
            border-radius: 2rem;
            padding: 0.3rem 0.5rem;
        }
        .nav-btns a {
            text-decoration: none;
            background: #001aafff;
            color: #fff;
            font-size: 1.1rem;
            border-radius: 1.25rem;
            padding: 0.2rem 1rem;
            box-shadow: 0 0.125rem 0.5rem #0002;
            transition: background 0.5s;
            display: flex;
            align-items: center;
        }
        .nav-btns a:hover { background: #000080; }
        .nav-btns h3 { margin: 0; display: inline; font-size: 1.05rem;}
        .nav-btns .circle {
            width: 2.25rem; height: 2.25rem;
            border-radius: 50%;
            background: #eee;
            display: flex; align-items: center; justify-content: center;
        }
        .nav-btns img { width: 1.75rem; height: 1.75rem; border-radius: 50%; background: #000080}

        .carrusel-container { max-width: 68.75rem; margin: 2rem auto; padding-top: 3.5rem; padding-bottom: 5rem; }
        .carrusel-titulo {
            font-size: 1.6rem;
            color: #fff;
            margin-bottom: 2rem;
            text-align: center;
            font-weight: bold;
            letter-spacing: 1px;
            text-transform: uppercase;
            font-family: 'HovesExpandedDemiBold';
        }
        .carrusel {
            display: flex;
            overflow-x: auto;
            gap: 1.5rem;
            padding-bottom: 1rem;
        }
        .libro {
            min-width: 8.75rem;
            background: #fff;
            border-radius: 0.625rem;
            box-shadow: 0 0.125rem 0.5rem #0001;
            text-align: center;
            padding: 1rem 0.5rem;
        }
        .libro img {
            width: 5rem;
            height: 6.875rem;
            object-fit: cover;
            border-radius: 0.375rem;
            margin-bottom: 0.5rem;
        }

        .libro-nombre { font-size: 1rem; color: #222; font-weight: bold; }
        @media (max-width: 900px) {
            nav {
                width: 90vw;
                max-width: 90vw;
            }
        }
        @media (max-width: 700px) {
            nav { flex-direction: column; gap: 1rem; width: 98vw; max-width: 98vw; }
        }
    </style>
    <header>
        <nav>
            <a href="index.php">
                <img src="img/icono.png" class="iconoimg" alt="Leeya icono">
            </a>
            <div class="nav-btns">
                <a href="#"><h3>EXPLORAR</h3></a>
                <a href="#"><h3>+</h3></a>
                <a href="#"><h3>MIS LIBROS</h3></a>
                <span class="circle">
                    <img src="img/user.png" alt="Usuario">
                </span>
            </div>
        </nav>
    </header>

    <main>

        <div class="panels">
            <div class="panel-interno">
                <div class="panel1">
                    <p class="litle-text">LIBROS AL ALCANCE DE TODOS: PUBLICA O ADQUIERE TUS LIBROS FAVORITOS HOY DESDE LA COMODIDAD DE TU HOGAR</p>
                </div>
                <div class ="panel2">
                    <img src="img/libros.png" alt="Libros">
                </div>
            </div>
        </div>
        
        <style>
            .panels{
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 1rem;
                width: 100%;
                max-width: 100vw;
                height: auto;
                background: linear-gradient(to bottom,
                #000000 0%,
                #000000 45%,
                #000080 90%,
                #000080 100%);
                padding-bottom: 3.5rem;
                padding-top: 1rem;
                margin: 0 auto;
            }

            .panel-interno{

                margin: 0;
                display: flex;
                align-items: center;
                justify-content: center;
                max-width: 70vw;
                height: auto;
            }

            .panel1 {
                width: 28rem;
                margin-right: -1rem;
                margin-top: -0.5rem;
                color: #ffffffff;
                align-items: center;
                text-align: justify;
                font-size: 1.3rem;
            }

            .panel2{
                align-items: center;
                width: 30rem;
                text-align: center;
                max-width: 50rem;
                margin: 0 auto;
            }

            .panel2 img {
                width: 60%;
                height: auto;
            }

            .litle-text {
                font-family: 'HovesBold';
                margin: 0;
            }

        </style>

        <img class="panel-separator" src="img/separador.png" alt="Separador">


        <style>

            .panel-separator {
                padding-bottom: 4rem;
                display: block;
                width: 100%;
                max-width: 100vw;
                height: 1rem;
                object-fit: cover;
                background: none;
                border: none;
            }

        </style>
        
        <div class="panel-content">
            <div class="tweet-wrapper">
                <blockquote class="twitter-tweet"><p lang="en" dir="ltr">Get any book with Leeya!</p>&mdash; readleeya (@readleeya) <a href="https://twitter.com/readleeya/status/1945965007847477482?ref_src=twsrc%5Etfw">July 17, 2025</a></blockquote> <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
            </div>

            <video autoplay muted loop playsinline preload="auto" poster="poster.jpg">
                <source src="vid/udbiblio.mp4" type="video/mp4">
                    Tu navegador no soporta el video HTML5.
            </video>

        </div>


            <style>

                .panel-content {
                    display: flex;
                    align-items: center;
                    gap: 6rem;
                    width: 80vw;
                    max-width: 80vw;
                    margin: 0 auto;
                    overflow-x: auto;
                    justify-content: center;
                    padding-bottom: 1rem;
                }

                .tweet-wrapper {
                    width: 30rem;
                    height: auto;
                    box-shadow: 0 5px 8px rgba(255, 255, 255, 0.11);
                    border-radius: 0.5rem;
                    overflow: hidden;
                    display: inline-block;
                    vertical-align: top;
                }

                .twitter-tweet {
                    margin: 0 !important;
                }

                video {
                    width: 30rem;
                    max-width: 30rem;
                    border-radius: 0.5rem;
                    height: auto;
                    width: 100%;
                    display: block;
                    box-shadow: 0 5px 8px rgba(255, 255, 255, 0.11);
                }

                .panel1footer {
                    padding-top:2rem;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    gap:4em;
                }

                .panel1footer img{
                    width: 7rem;
                    height: auto;
                }

                .shortfooter {
                    font-size: 1.2rem;
                    font-family: 'HovesRegular';
                }

                .panelfooter2{
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }

            </style>  

        <div class="carrusel-container">
            <div class="carrusel-titulo">PUBLICACIONES RECIENTES</div>
            <div class="carrusel">
                <?php
                $libros = [
                    ['nombre' => 'Cien años de soledad', 'img' => 'img/libro1.jpg'],
                    ['nombre' => 'Rayuela', 'img' => 'img/libro2.jpg'],
                    ['nombre' => 'El amor en los tiempos del cólera', 'img' => 'img/libro3.jpg'],
                    ['nombre' => 'Pedro Páramo', 'img' => 'img/libro4.jpg'],
                    ['nombre' => 'Don Quijote', 'img' => 'img/libro5.jpg'],
                    ['nombre' => 'La sombra del viento', 'img' => 'img/libro6.jpg'],
                    ['nombre' => 'Ficciones', 'img' => 'img/libro7.jpg'],
                    ['nombre' => 'El túnel', 'img' => 'img/libro8.jpg'],
                    ['nombre' => 'Aura', 'img' => 'img/libro9.jpg'],
                    ['nombre' => 'La casa de los espíritus', 'img' => 'img/libro10.jpg'],
                ];
                foreach ($libros as $libro): ?>
                    <div class="libro">
                        <img src="<?= htmlspecialchars($libro['img']) ?>" alt="<?= htmlspecialchars($libro['nombre']) ?>">
                        <div class="libro-nombre"><?= htmlspecialchars($libro['nombre']) ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="bookbox-container">

            <div class="bookbox">

                <div class="functionsbook">
                    <button><img src="img/like.png" alt="Agrega este libro a tus favoritos"></button>
                    <h3 class="statusbook">Estado</h3>
                </div>

                <div class="imagenbox">
                    <img src="img/libropic.png" alt="Libro de la publicación reciente">
                </div>

                <div class="infolibro">    
                    <h3 class="TituloLibro">Título del Libro</h3>
                    <h4 class="PrecioLibro">$ Precio</h4>
                    <button class="AdquirirLibro"><a href="#">Adquirir</a></button>
                </div>    

            </div>

        </div>

        <style> /* Estilo de los últimos libros publicados */
            .bookbox-container {
                display: flex;
                justify-content: center;
                padding: 2rem 0;
            }

            .bookbox {
                background: #fff;
                border-radius: 0.625rem;
                box-shadow: 0 0.125rem 0.5rem #0001;
                width: 20rem;
                text-align: center;
                padding: 1rem;
            }

            .functionsbook {
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .functionsbook button {
                background: none;
                border: none;
            }

            .functionsbook img {
                width: 1.5rem;
            }

            .statusbook {
                font-size: 1rem;
                color: #555;
            }

            .imagenbox img {
                width: 100%;
                height: auto;
                border-radius: 0.375rem;
            }

            .infolibro {
                margin-top: 1rem;
            }

            .TituloLibro {
                font-size: 1.2rem;
                font-weight: bold;
            }

            .PrecioLibro {
                font-size: 1.1rem;
                color: #888;
            }

            .AdquirirLibro a {
                text-decoration: none;
                color: #fff;
                background-color: #000080;
                padding: 0.5rem 1rem;
                border-radius: 0.375rem;
                transition: background 0.5s;
            }

            .AdquirirLibro a:hover {
                background-color: #001aafff;
            }

        </style>

    </main>

    <footer>

        <div class="panel1footer">

            <img src="img/icon.png" class="iconoimg" alt="Leeya icono">

            <div class="panel1footertext">
                <p class="shortfooter">Leeya: un espacio de acceso a la literatura y contenido bibliográfico dedicado a los estudiantes de la Universidad Distrital Francisco José de Caldas.</p>                
            </div>

        </div>

        <div class="panel2footer">

            <p>&copy; 2025 Leeya. Todos los derechos reservados.</p>
            <p>Un proyecto de la Universidad Distrital Francisco José de Caldas</p>

        </div>        



                  

    </footer>

    <style>
        footer {
            background: #eeeeeeff;      
            color: #fff;
            text-align: center;
            padding: 1rem;
            font-size: 1rem;
            font-family: 'HovesExpandedBold';
            padding-bottom: 3.5rem;
        }

        footer p {
            color: #000;
        }

        .panel1footertext{
            width: 50%;
        }

    </style>

</body>
</html>