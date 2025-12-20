<?php

require_once 'auth_functions.php';
require_once 'database.php';

$pdo = getDBConnection();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel | Leeya</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="img/icon.png" type="image/png">
    <style>
        body {
            margin: 0;
            font-family: 'HovesDemiBold';
            background-color: #000;
            color: #fff;
        }

        header {
            width: 100%;
            background: linear-gradient(to bottom, #000 0%, #001aafff 80%);
            padding-top: 1rem;
            padding-bottom: 1rem;
        }

        nav {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: clamp(1rem, 3vw, 2.5rem);
            width: 75vw;
            max-width: 75vw;
            margin: auto;
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
            gap: 0.5rem;
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

        .nav-btns a:hover {
            background: #000080;
        }

        .nav-btns h3 {
            margin: 0;
            font-size: 1.05rem;
        }
        .nav-btns .circle {
            width: 2.25rem;
            height: 2.25rem;
            border-radius: 50%;
            background: #001aafff;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.8s;
        }

        .nav-btns .circle:hover {
            background: #000080;
        }

        .nav-btns img {
            width: 1.6rem;
            height: 1.6rem;
            object-fit: contain;
        }

        footer {
            text-align: center;
            background: linear-gradient(to top, #000 0%, #001aafff 90%);
            color: #fff;
            padding: 3rem 1rem 2rem 1rem;
            font-family: 'HovesMedium';
            font-size: 1rem;
        }

        footer img {
            height: 3rem;
            display: block;
            margin: 0 auto 1rem auto;
        }

        footer p {
            margin: 0.5rem 0;
        }
    </style>
</head>

<body>

    <header>
        <nav>
            <a href="adminpanel.php">
                <img src="img/icono.png" class="iconoimg" alt="Leeya icono">
            </a>
            <div class="nav-btns">
                <a href="adminpanel.php">
                    <h3>EXPLORAR</h3>
                </a>
                <a href="userlist.php">
                    <h3>USUARIOS</h3>
                </a>
                <a href="adminreports.php">
                    <h3>REPORTES</h3>
                </a>
                <a href="logout.php">
                    <h3>CERRAR SESIÓN</h3>
                </a>
                
            </div>
        </nav>
    </header>

    <main style="min-height: 70vh; text-align:center; padding-top: 4rem;">
        <h1>Bienvenido a Leeya</h1>
        <p>Panel principal de administrador</p>
                <div class="panel-content">
            <div class="tweet-wrapper">
                <blockquote class="twitter-tweet">
                    <p lang="en" dir="ltr">Get any book with Leeya!</p>&mdash; readleeya (@readleeya) <a
                        href="https://twitter.com/readleeya/status/1945965007847477482?ref_src=twsrc%5Etfw">July 17,
                        2025</a>
                </blockquote>
                <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
            </div>

            <video autoplay muted loop playsinline preload="auto" poster="img/icono.png">
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
                padding-bottom: 4vw;
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
                padding-top: 2rem;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 4em;
            }

            .panel1footer img {
                width: 7rem;
                height: auto;
            }

            .shortfooter {
                font-size: 1.2rem;
                font-family: 'HovesRegular';
            }

            .panelfooter2 {
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .carrusel-titulo {
                padding-top: 5rem;
                font-size: 1.8rem;
                color: #fff;
                font-family: 'HovesExpandedDemiBold';
            }
        </style>
    </main>
    


    <footer>
        <img src="img/icono.png" alt="Leeya logo">
        <p>Leeya: un espacio de acceso a la literatura y contenido bibliográfico dedicado a los estudiantes de la Universidad Distrital Francisco José de Caldas.</p>
        <p>© 2025 Leeya. Todos los derechos reservados.</p>
        <p>Un proyecto de la Universidad Distrital Francisco José de Caldas</p>
    </footer>

</body>
</html>