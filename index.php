<?php

session_start();

require_once 'auth_functions.php';
require_once 'database.php';

/* Modificar para redirigir a la página de panel de administrador si el usuario es un administrador
 if (isset($_SESSION['user_id']) && ($_SESSION['user_role'] ?? '') === 'admin') {
    header('Location: admin-dashboard.php'); // Redirige al panel de administrador
    exit(); // Detiene la ejecución del script para asegurar la redirección
}*/

$is_logged_in = false;
$user_role = '';

refreshSessionUser();

if (isLoggedIn()) {

    if (isset($_SESSION['user_id'])) {
        $is_logged_in = true;
        $user_name = htmlspecialchars($_SESSION['user_name'] ?? '');
        $user_role = htmlspecialchars($_SESSION['user_role'] ?? 'user');
    }

}

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
        html {
            font-size: 15px;
        }

        body {
            margin: 0;
            font-family: 'HovesDemiBoldItalic';
            background: #000;
        }

        header {
            background: transparent;
            box-shadow: none;
        }

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
            font-family: 'HovesExpandedBold';
            box-sizing: border-box;
        }

        .carrusel-container {
            max-width: 68.75rem;
            margin: 2rem auto;
            padding-top: 3.5rem;
            padding-bottom: 5rem;
        }

        .carrusel-titulo {
            font-size: 1.4rem;
            color: #fff;
            margin-bottom: 1.5rem;
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

        .libro-nombre {
            font-size: 1rem;
            color: #222;
            font-weight: bold;
        }

        @media (max-width: 900px) {
            nav {
                width: 90vw;
                max-width: 90vw;
            }
        }

        @media (max-width: 700px) {
            nav {
                flex-direction: column;
                gap: 1rem;
                width: 98vw;
                max-width: 98vw;
            }
        }
    </style>

</head>

<body>

    <header>
        <nav>
            <a href="index.php">
                <img src="img/icono.png" class="iconoimg" alt="Leeya icono">
            </a>
            <div class="nav-btns">

                <a href="explore.php">
                    <h3>EXPLORAR</h3>
                </a>

                <?php if ($is_logged_in): ?>
                    <a href="newbook.php">
                        <h3>+</h3>
                    </a>

                    <a href="mybooks.php">
                        <h3>MIS LIBROS</h3>
                    </a>


                <?php elseif (!$is_logged_in): ?>

                    <a href="login.php">
                        <h3>INICIAR SESIÓN</h3>
                    </a>

                <?php endif; ?>

                <?php if ($is_logged_in): ?>

                    <a class="circle" href="#">
                        <img src="img/noti.png" alt="Notificación" class="noti-icon">
                    </a>

                    <a class="circle" href="user.php">
                        <img src="img/user.png" alt="Usuario" class="user">
                    </a>

                <?php endif; ?>

                <style>
                    .iconoimg {
                        height: 3.5rem;
                        width: auto;
                        margin-right: -1.5rem;
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
                        display: inline;
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
                        box-sizing: border-box;
                        transition: background 0.8s;
                        cursor: pointer;
                    }

                    .nav-btns img {
                        width: 1.75rem;
                        height: 1.75rem;
                        border-radius: 50%;
                    }

                    .nav-btns .noti-icon {
                        width: 1.73rem !important;
                        height: auto !important;
                        object-fit: contain;
                        display: block;
                    }

                    .nav-btns .user {
                        width: 1.73rem !important;
                        height: auto !important;
                        object-fit: contain;
                        display: block;
                    }

                    .nav-btns .circle {
                        width: 2.25rem;
                        height: 2.25rem;
                        border-radius: 50%;
                        background: #001aafff;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        box-sizing: border-box;
                    }

                    .nav-btns .circle img {
                        width: 1.3rem;
                        height: 1.3rem;
                        object-fit: contain;
                        display: block;
                        margin: -0.1rem;
                    }

                    .nav-btns .circle:hover {
                        background: #000080;
                    }
                </style>

            </div>
        </nav>
    </header>

    <main>

        <div class="panels">
            <div class="panel-interno">
                <div class="panel1">
                    <p class="litle-text">LIBROS AL ALCANCE DE TODOS: PUBLICA O ADQUIERE TUS LIBROS FAVORITOS HOY DESDE
                        LA COMODIDAD DE TU HOGAR</p>
                </div>
                <div class="panel2">
                    <img src="img/libros.png" alt="Libros">
                </div>
            </div>
        </div>

        <style>
            .panels {
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

            .panel-interno {

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

            .panel2 {
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
                font-family: 'HovesDemiBoldItalic';
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
                <blockquote class="twitter-tweet">
                    <p lang="en" dir="ltr">Get any book with Leeya!</p>&mdash; readleeya (@readleeya) <a
                        href="https://twitter.com/readleeya/status/1945965007847477482?ref_src=twsrc%5Etfw">July 17,
                        2025</a>
                </blockquote>
                <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
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

        <?php
        $latest_books = getLatestBooks(4, $is_logged_in ? $_SESSION['user_id'] : null);
        ?>

        <?php if (!empty($latest_books)): ?>
            <h2 class="carrusel-titulo">Últimos libros publicados</h2>
            <div class="bookbox-container">
                <?php foreach ($latest_books as $book): ?>
                    <div class="fullbook">
                        <div class="bookbox">
                            <div class="functionsbook">
                                <button class="likebutton"><img src="img/like.png" class="likepic"
                                        alt="Agrega este libro a tus favoritos"></button>
                                <h3 class="statusbook"><?= htmlspecialchars($book['typeof']) ?></h3>
                            </div>
                            <div class="imagenbox">
                                <img src="<?= htmlspecialchars($book['bookpic']) ?>" alt="Libro publicado">
                            </div>
                        </div>
                        <div class="infolibro">
                            <h3 class="TituloLibro"><?= htmlspecialchars($book['name']) ?></h3>
                            <?php if ($book['price'] !== null): ?>
                                <h4 class="PrecioLibro">$<?= htmlspecialchars($book['price']) ?></h4>
                            <?php endif; ?>
                            <div class="AdquirirLibro">
                                <?php if ($is_logged_in): ?>
                                    <a href="pickedbook.php?id=<?= $book['id'] ?>">Adquirir</a>
                                <?php else: ?>
                                    <a href="login.php">Inicia sesión para adquirir</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <style>
            /* Estilo de los últimos libros publicados */
            .bookbox-container {
                display: flex;
                flex-wrap: wrap;
                gap: 2vw;
                justify-content: center;
                align-items: stretch;
                width: 100%;
                padding-top: 2vw;
                padding-bottom: 4vw;
                box-sizing: border-box;
            }

            /* Editar el porcentaje de uso de la pantalla, no està manejado de manera responsiva al 100%*/

            .PrecioLibro {
                margin: 0 0 0.5vw 0;
                min-height: 1.5em;
                color: #222;
                font-size: 1vw;
            }

            .fullbook {
                background: #fff;
                border-radius: 1vw;
                box-shadow: 0 0.5vw 2vw rgba(0, 0, 0, 0.08);
                width: 22vw;
                min-width: 180px;
                max-width: 98vw;
                display: flex;
                flex-direction: column;
                align-items: stretch;
                min-height: 32vw;
                margin-bottom: 2vw;
                overflow: hidden;
            }

            .bookbox {
                width: 100%;
                aspect-ratio: 1/1.2;
                border-radius: 1vw;
                background: #f5f5f5;
                position: relative;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .imagenbox {
                width: 80%;
                height: 80%;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: auto;
            }

            .imagenbox img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                border-radius: 1vw;
            }

            .infolibro {
                padding: 1vw 1vw 0.5vw 1vw;
                width: 100%;
                flex: 1 1 auto;
                display: flex;
                flex-direction: column;
                justify-content: flex-start;
                align-items: flex-start;
                box-sizing: border-box;
            }

            .functionsbook {
                position: absolute;
                top: 0.5rem;
                left: 0.5rem;
                display: flex;
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
                width: 90%;
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

            .PrecioLibro {
                margin: 0;
                padding: 0;
            }

            .TituloLibro {
                font-size: 1.2vw;
                font-weight: bold;
                margin: 0 0 0.5vw 0;
                width: 100%;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .AdquirirLibro {
                margin-top: auto;
                width: 100%;
                display: flex;
                justify-content: flex-end;
            }

            .AdquirirLibro a {
                text-decoration: none;
                color: #fff;
                background-color: #000080;
                padding: 0.5vw 1vw;
                border-radius: 0.5vw;
                transition: background 0.5s;
                font-size: 1vw;
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
                <p class="shortfooter">Leeya: un espacio de acceso a la literatura y contenido bibliográfico dedicado a
                    los estudiantes de la Universidad Distrital Francisco José de Caldas.</p>
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

        .panel1footertext {
            width: 50%;
        }
    </style>

</body>

</html>