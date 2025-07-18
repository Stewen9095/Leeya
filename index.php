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
            padding: 3.2rem 0rem 2.8rem 0rem;
            font-family: 'Montserrat-Bold';
            box-sizing: border-box;
        }
        .iconoimg {
            height: 3.5rem;
            width: auto;
            padding-bottom: 0.9rem;
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
            background: #001AAF;
            color: #fff;
            font-weight: bold;
            font-size: 1.1rem;
            border-radius: 1.25rem;
            padding: 0.2rem 1rem;
            box-shadow: 0 0.125rem 0.5rem #0002;
            transition: background 0.2s;
            display: flex;
            align-items: center;
        }
        .nav-btns a:hover { background: #000080; }
        .nav-btns h3 { margin: 0; display: inline; font-size: 1.2rem;}
        .nav-btns .circle {
            width: 2.25rem; height: 2.25rem;
            border-radius: 50%;
            background: #eee;
            display: flex; align-items: center; justify-content: center;
        }
        .nav-btns img { width: 1.75rem; height: 1.75rem; border-radius: 50%; background: #000080}

        .carrusel-container { max-width: 68.75rem; margin: 2rem auto; }
        .carrusel-titulo {
            font-size: 1.2rem;
            color: #fff;
            margin-bottom: 1rem;
            text-align: center;
            font-weight: bold;
            letter-spacing: 1px;
            text-transform: uppercase;
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
            <div class="panel1">
                <p class="litle-text">LIBROS AL ALCANCE DE TODOS: PUBLICA O ADQUIERE TUS LIBROS FAVORITOS HOY DESDE LA COMODIDA DE TU HOGAR</p>
            </div>
            <div class ="panel2">
                <img src="img/libros.png" alt="Libros">
            </div>
        </div>
        
        <style>
            .panels{
                display: flex;
                align-items: center;
                gap: 3rem;
                width: 82vw;
                min-width: 18rem;
                max-width: 82vw;
                margin: 0 auto;
                height: auto;
            }

            .panel1 {
                width: 58%;
                display: 1;
                color: #ffffffff;
                text-align: justify;
                font-size: 1.8rem;
                font-family: 'Montserrat-Regular';
            }

            .panel2{
                width: 42%;
                display: 1;
                text-align: center;
                max-width: 50rem;
            }

            .panel2 img {
                width: 88%;
                height: auto;
            }
        </style>

        <img class="panel-separator" src="img/separador.png" alt="Separador">


        <style>

.panel-separator {
    display: block;
    width: 100vw;         /* Ocupa todo el ancho de la ventana */
    max-width: 100vw;
    min-width: 0;
    height: 0.4rem;       /* Ajusta el alto a tu gusto */
    margin: 1.5rem 0 1.5rem calc(50% - 50vw); /* Centra la imagen aunque el contenido esté centrado */
    object-fit: cover;    /* Rellena el ancho sin deformar la imagen */
    background: none;
    border: none;
    padding: 0;
}

        </style>
        
        <div class="panel-content">
            <blockquote class="twitter-tweet"><p lang="en" dir="ltr">Get any book with Leeya!</p>&mdash; readleeya (@readleeya) <a href="https://twitter.com/readleeya/status/1945965007847477482?ref_src=twsrc%5Etfw">July 17, 2025</a></blockquote> <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>

            <video controls>
                <source src="video/promo.mp4" type="video/mp4">
                Tu navegador no soporta el elemento de video.
            </video>

        </div>

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
    </main>

    <footer>

    </footer>

</body>
</html>