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

    @font-face {
        font-family: 'Montserrat';
        src: url('fonts/Montserrat-Thin.ttf') format('truetype');
        font-weight: normal;
        font-style: normal;
    }

    @font-face {
        font-family: 'Montserrat-ThinItalic';
        src: url('fonts/Montserrat-ThinItalic.ttf') format('truetype');
        font-weight: normal;
        font-style: italic;
    }

    @font-face {
        font-family: 'Montserrat-ExtraLight';
        src: url('fonts/Montserrat-ExtraLight.ttf') format('truetype');
        font-weight: normal;
        font-style: normal;
    }
    @font-face {
        font-family: 'Montserrat-ExtraLightItalic';
        src: url('fonts/Montserrat-ExtraLightItalic.ttf') format('truetype');
        font-weight: normal;
        font-style: italic;
    }

    @font-face {
        font-family: 'Montserrat-Light';
        src: url('fonts/Montserrat-Light.ttf') format('truetype');
        font-weight: normal;
        font-style: normal;
    }

    @font-face {
        font-family: 'Montserrat-LightItalic';
        src: url('fonts/Montserrat-LightItalic.ttf') format('truetype');
        font-weight: normal;
        font-style: italic;
    }

    @font-face {
        font-family: 'Montserrat-Regular';
        src: url('fonts/Montserrat-Regular.ttf') format('truetype');
        font-weight: normal;
        font-style: normal;
    }

    @font-face {
        font-family: 'Montserrat-Italic';
        src: url('fonts/Montserrat-Italic.ttf') format('truetype');
        font-weight: normal;
        font-style: italic;
    }

    @font-face {
        font-family: 'Montserrat';
        src: url('fonts/Montserrat-Medium.ttf') format('truetype');
        font-weight: normal;
        font-style: normal;
    }

    @font-face {
        font-family: 'Montserrat-MediumItalic';
        src: url('fonts/Montserrat-MediumItalic.ttf') format('truetype');
        font-weight: normal;
        font-style: italic;
    }

    @font-face {
        font-family: 'Montserrat-SemiBold';
        src: url('fonts/Montserrat-SemiBold.ttf') format('truetype');
        font-weight: normal;
        font-style: normal;
    }
    
    @font-face {
        font-family: 'Montserrat-SemiBoldItalic';
        src: url('fonts/Montserrat-SemiBoldItalic.ttf') format('truetype');
        font-weight: normal;
        font-style: italic;
    }

    @font-face {
        font-family: 'Montserrat-Bold';
        src: url('fonts/Montserrat-Bold.ttf') format('truetype');
        font-weight: normal;
        font-style: normal;
    }

    @font-face {
        font-family: 'Montserrat-BoldItalic';
        src: url('fonts/Montserrat-BoldItalic.ttf') format('truetype');
        font-weight: normal;
        font-style: italic;
    }

    @font-face {
        font-family: 'Montserrat-ExtraBold';
        src: url('fonts/Montserrat-ExtraBold.ttf') format('truetype');
        font-weight: normal;
        font-style: normal;
    }

    @font-face {
        font-family: 'Montserrat-ExtraBoldItalic';
        src: url('fonts/Montserrat-ExtraBoldItalic.ttf') format('truetype');
        font-weight: normal;
        font-style: italic;
    }

    @font-face {
        font-family: 'Montserrat-Black';
        src: url('fonts/Montserrat-Black.ttf') format('truetype');
        font-weight: normal;
        font-style: normal;
    }

    @font-face {
        font-family: 'Montserrat-BlackItalic';
        src: url('fonts/Montserrat-BlackItalic.ttf') format('truetype');
        font-weight: normal;
        font-style: italic;
    }

        body { margin:0; font-family: 'Montserrat'; background:#000; }
        header { background: transparent; box-shadow: none; }
        
        nav {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            gap: clamp(1rem, 3vw, 2.5rem);
            padding-top: clamp(0.5rem, 4vw, 3rem);
            padding-bottom: clamp(0.5rem, 2vw, 0.2rem);
            width: 85vw;
            max-width: 85vw;
            min-width: 18rem;
            margin-left: auto;
            margin-right: auto;
            padding-left: 0;
            padding-right: 0;
            font-family: 'Montserrat-Bold';
            
        }
        .iconoimg {
            height: 3.5rem;
            width: auto;
            padding-bottom: 0.9rem;
        }
        .nav-btns { display: flex; gap: 0.8rem; align-items: center; }
        .nav-btns a {
            text-decoration: none;
            background: #001AAF;
            color: #fff;
            font-weight: bold;
            font-size: 1.1rem;
            border-radius: 1.25rem;
            padding: 0.2rem 1.2rem;
            box-shadow: 0 0.125rem 0.5rem #0002;
            transition: background 0.2s;
            display: flex;
            align-items: center;
        }
        .nav-btns a:hover { background: #000080; }
        .nav-btns h3 { margin: 0; display: inline; }
        .nav-btns .circle {
            width: 2.25rem; height: 2.25rem;
            border-radius: 50%;
            background: #eee;
            display: flex; align-items: center; justify-content: center;
        }
        .nav-btns img { width: 1.75rem; height: 1.75rem; border-radius: 50%; background: #000080}
        .paneles {
            display: flex;
            gap: 2rem;
            justify-content: center;
            margin: 2rem 0;
            width: 85vw;
            max-width: 85vw;
            min-width: 18rem;
            margin-left: auto;
            margin-right: auto;
            font-family: 'Montserrat';
        }
        .panel {
            flex:1;
            min-width: 0;
            height: 11.25rem;
            border-radius: 0.4rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
            color: #fff;
            font-weight: bold;
            font-size: 1.2rem;
        }
        .panel1 { background: #001AAF; }
        .panel2 { background: #000080; display: flex; flex-direction: column; }
        .panel img { max-width: 3.75rem; max-height: 3.75rem; margin-top: 1rem; }
        .panel-text, .panel-text2 {
            text-align: center;
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
            font-weight: bold;
        }
        .panel-text2 { margin-right: 0; }
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

        .panel1-img, .panel2-img {
            width: 1rem;
            height: auto;
        }

        .libro-nombre { font-size: 1rem; color: #222; font-weight: bold; }
        @media (max-width: 900px) {
            .paneles {
                flex-direction: column;
                width: 90vw;
                max-width: 90vw;
            }
            nav {
                width: 90vw;
                max-width: 90vw;
            }
            .panel { height: 10rem; }
        }
        @media (max-width: 700px) {
            nav { flex-direction: column; gap: 1rem; width: 98vw; max-width: 98vw; }
            .paneles { width: 98vw; max-width: 98vw; }
        }
    </style>
    <header>
        <nav>
            <img src="img/icono.png" class="iconoimg" alt="Leeya icono">
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
        <div class="paneles">
            <div class="panel panel1">
                <div class="panel-text">LIBROS AL ALCANCE DE TODOS</div>
                <img src="img/panel1.png" alt="Panel 1" class="panel1-img">
            </div>
            <div class="panel panel2">
                <img src="img/panel2.png" alt="Panel 2" class="panel2-img">
                <div class="panel-text2">PUBLICA O ADQUIERE TUS LIBROS HOY</div>
            </div>
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