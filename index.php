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
updateExpiredAuctions();

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
        body {
            margin: 0;
            font-family: 'HovesDemiBold';
            background: white;
            width: 100vw;
            height: 100vh;
        }

        header {
            box-sizing: border-box;
            width: 100%;
            max-width: 1400px;
            margin: 0 auto;
            position: relative;
            justify-content: center;
            display: flex;
            padding-top: 2rem;
            padding-inline: 20px;
            font-size: 14px;
        }

        nav {
            display: flex;
            flex-wrap: wrap;
            width: 100%;
            background-color: black;
            gap: .6rem;
            position: relative;
            padding: .3% 0 .3% 0;
            justify-content: center;
            align-items: center;
            border-radius: .5rem;
            box-shadow: 0 6px 6px rgba(0, 0, 0, 0.2), 0 0 20px rgba(0, 0, 0, 0.1);
            transition: all 2s cubic-bezier(0.175, 0.885, 0.32, 2.2);
        }

        nav:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 5px 5px rgba(0, 0, 0, 0.12);
        }

        nav a {
            width: 100%;
            flex: 1 1 300px;
            display: flex;
            box-sizing: border-box;
            border-radius: 1.2rem;
            position: relative;

            h3 {
                margin: auto;
            }
        }

        nav a:nth-child(1) {
            flex: 0 0 100px;
            position: relative;
            top: -5px;
        }

        nav a:nth-child(1) .iconoimg {
            width: 100%;
        }

        nav a:nth-child(2) {
            background-color: black;
            flex: 0 0 140px;
            border: .3rem solid white;
            text-decoration: none;
            color: white;
            box-shadow: 0 5px 5px rgba(255, 255, 255, 0.1), 0 0 8px rgba(255, 255, 255, 0.12);
        }


        /* Cel */
        @media (max-width: 500px) {

            body {
                margin: 0;
                font-family: 'HovesDemiBold';
                background: white;
                width: 100vw;
                height: 100vh;
            }

            header {
                box-sizing: border-box;
                width: 100%;
                min-width: 60px;
                margin: 0 auto;
                position: relative;
                justify-content: center;
                display: flex;
                padding-top: 1rem;
                padding-inline: 10px;
                font-size: 10px;
            }

            nav {
                flex-wrap: wrap;
                display: flex;
                flex-direction: row;
                width: 100%;
                background-color: black;
                gap: .6rem;
                position: relative;
                justify-content: center;
                align-items: center;
                border-radius: .5rem;
                box-shadow: 0 6px 6px rgba(0, 0, 0, 0.2), 0 0 20px rgba(0, 0, 0, 0.1);
                transition: all 2s cubic-bezier(0.175, 0.885, 0.32, 2.2);
                padding-bottom: 1rem;
                padding-inline: 15px;
            }

            nav:hover {
                transform: translateY(-2px);
                box-shadow: 0 5px 5px 5px rgba(0, 0, 0, 0.12);
            }

            nav a {
                width: 90%;
                flex: 0 0 auto;
                box-sizing: border-box;
                border-radius: 1.2rem;
                position: relative;
                display: flex;
                align-items: center;
                justify-content: center;
                

                h3 {
                    margin: 0 auto;
                    padding: 0;
                }
            }

            nav a:nth-child(1) {
                position: relative;
                justify-content: center;
                align-items: center;
                flex: 0 0 auto;
                display: flex;
                top: 5px;
            }

            nav a:nth-child(1) .iconoimg {
                width: 35%;
                padding: .5rem;
            }

            nav a:nth-child(2) {
                flex: 0 1 25%;
                width: 80%;
                padding: 2px 0 2px 0;
                background-color: black;
                border: .2rem solid white;
                text-decoration: none;
                color: white;
                box-shadow: 0 5px 5px rgba(255, 255, 255, 0.1), 0 0 8px rgba(255, 255, 255, 0.12);
                padding: 4px 0 4px 0;
                overflow: hidden;
            }

            nav a:nth-child(3) {
                width: 80%;
                max-height: 35px;
                padding: 2px 0 2px 0;
                background-color: black;
                border: .2rem solid white;
                text-decoration: none;
                color: white;
                box-shadow: 0 5px 5px rgba(255, 255, 255, 0.1), 0 0 8px rgba(255, 255, 255, 0.12);
            }


        }
    </style>

</head>

<body>

    <header>

        <nav>

            <a href="index.php" class="image-logo">
                <img src="img/icono.png" class="iconoimg" alt="Leeya icono">
            </a>

            <a href="explore.php">
                <h3>EXPLORAR</h3>
            </a>

            <?php if ($is_logged_in):

                $pending_counts = getPendingProposalsCount($_SESSION['user_id']);
                $total_pending = $pending_counts['sent'] + $pending_counts['received'];
                $badge_text = $total_pending > 9 ? '+9' : ($total_pending > 0 ? $total_pending : '');
                ?>

                <a href="newbook.php" class="plus">
                    <h3>+</h3>
                </a>

                <style>
                    nav a:nth-child(3) {
                        background-color: black;
                        border: .3rem solid white;
                        flex: 0 1 35px;
                        color: white;
                        text-decoration: none;
                        box-shadow: 0 5px 5px rgba(255, 255, 255, 0.1), 0 0 8px rgba(255, 255, 255, 0.12);
                    }

                    @media (max-width: 500px) {
                        nav a:nth-child(3) {
                            padding: 4px 0 4px 0;
                            flex: 0 1 30px;
                            max-width: 30px;
                            max-height: 35px;
                            background-color: black;
                            border: .2rem solid white;
                            text-decoration: none;
                            color: white;
                            box-shadow: 0 5px 5px rgba(255, 255, 255, 0.1), 0 0 8px rgba(255, 255, 255, 0.12);
                        }
                    }
                </style>


            <?php elseif (!$is_logged_in): ?>

                <a href="login.php">
                    <h3>INICIAR SESIÓN</h3>
                </a>

                <style>
                    nav a:nth-child(3) {
                        background-color: black;
                        border: .3rem solid white;
                        flex: 0 1 180px;
                        color: white;
                        text-decoration: none;
                        box-shadow: 0 5px 5px rgba(255, 255, 255, 0.1), 0 0 8px rgba(255, 255, 255, 0.12);
                    }
                </style>

            <?php endif; ?>

            <?php if ($is_logged_in): ?>

                <style>
                    nav .circle1 {
                        /* Nth child 4 when is signed up */
                        flex: 0 1 30px;
                        box-sizing: border-box;
                        position: relative;
                    }

                    nav .circle1 .noti-icon {
                        /* img for nth child 4 */
                        width: 95%;
                        box-sizing: border-box;
                    }

                    .numnoti {
                        position: absolute;
                        top: 10px;
                        left: 10px;
                        display: block;
                        background-color: black;
                        border: .3rem solid white;
                        border-radius: .5rem;
                        color: white;
                        margin-right: 1rem;
                        box-sizing: border-box;
                        height: 18px;
                        width: 18px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        box-shadow: 0 5px 5px rgba(255, 255, 255, 0.1), 0 0 8px rgba(255, 255, 255, 0.12);

                        p {
                            flex: 1;
                            margin: 0;
                            text-align: center;
                            font-size: 10px;
                        }

                    }

                    nav .circle2 {
                        /* Nth child 5 when is signed up */
                        background-color: black;
                        flex: 0 1 32px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        box-sizing: border-box;
                        border: .3rem solid white;
                        overflow: hidden;
                        box-shadow: 0 5px 5px rgba(255, 255, 255, 0.1), 0 0 8px rgba(255, 255, 255, 0.12);

                    }

                    nav .circle2 .user {
                        /* img for nth child 4 */
                        width: 100%;
                        overflow: hidden;

                    }

                    @media (max-width: 500px) {

                        nav .circle1 {
                            width: 80%;
                            flex: 0 1 auto;
                            max-width: 30px;
                            max-height: 35px;
                            background-color: black;
                            border: .2rem solid white;
                            text-decoration: none;
                            color: white;
                            box-shadow: 0 5px 5px rgba(255, 255, 255, 0.1), 0 0 8px rgba(255, 255, 255, 0.12);
                        }

                        nav .circle1 .noti-icon {
                            /* img for nth child 4 */
                            width: 95%;
                            box-sizing: border-box;
                        }

                        .numnoti {
                            position: absolute;
                            top: 10px;
                            left: 10px;
                            display: block;
                            background-color: black;
                            border: .3rem solid white;
                            border-radius: .5rem;
                            color: white;
                            margin-right: 1rem;
                            box-sizing: border-box;
                            height: 18px;
                            width: 18px;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            box-shadow: 0 5px 5px rgba(255, 255, 255, 0.1), 0 0 8px rgba(255, 255, 255, 0.12);

                            p {
                                flex: 1;
                                margin: 0;
                                text-align: center;
                                font-size: 10px;
                            }

                        }

                        nav .circle2 {
                            /* Nth child 5 when is signed up */
                            background-color: black;
                            flex: 0 1 32px;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            box-sizing: border-box;
                            border: .3rem solid white;
                            overflow: hidden;
                            box-shadow: 0 5px 5px rgba(255, 255, 255, 0.1), 0 0 8px rgba(255, 255, 255, 0.12);

                        }

                        nav .circle2 .user {
                            /* img for nth child 4 */
                            width: 100%;
                            overflow: hidden;

                        }

                    }
                </style>

                <a class="circle1" href="myproposals.php">
                    <img src="img/noti.png" alt="Notificación" class="noti-icon">
                    <?php if ($badge_text): ?>
                        <span class="numnoti">
                            <p><?= $badge_text ?></p>
                        </span>
                    <?php endif; ?>
                </a>

                <a class="circle2" href="user.php">
                    <img src="img/user.png" alt="Usuario" class="user">
                </a>


            <?php endif; ?>

        </nav>
    </header>


    <main>

        <div class="panel1">
            <div class="son1">
                <p>LIBROS AL ALCANCE DE TODOS: PUBLICA O ADQUIERE TUS LIBROS FAVORITOS HOY DESDE
                    LA COMODIDAD DE TU HOGAR</p>
            </div>
            <div class="son1">
                <img src="img/libros.png" alt="Libros">
            </div>
        </div>

        <style>
            .panel1 {
                display: flex;
                flex-wrap: wrap;
                padding-top: 30px;
                gap: 15px;
                width: 100%;
                max-width: 1150px;
                justify-content: center;
                align-items: stretch;
                margin: 0 auto;
                box-sizing: border-box;
                min-width: 0;
            }

            .son1:first-child {
                flex: 1 1 450px;
                box-sizing: border-box;
                position: relative;
                display: flex;
                justify-content: center;

                p {
                    width: 85%;
                    font-size: 28px;
                    overflow-wrap: break-word;
                    word-break: break-word;
                    align-self: center;
                }
            }

            .son1:last-child {
                background-color: black;
                flex: 0 1 360px;
                box-sizing: border-box;
                position: relative;
                display: flex;
                justify-content: center;
                padding: 0px 15px;
                box-shadow: 0 5px 5px rgba(0, 0, 0, 0.1), 0 0 8px rgba(0, 0, 0, 0.12);
                border-radius: .4 rem;


                img {
                    width: 100%;
                }
            }
        </style>

        <!--  
        <div class="panel2">
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

        -->

        <!--  

        <div class="latests-books">
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
                                <?php elseif ($book['price'] == null): ?>
                                    <h4 class="PrecioLibro">($) No aplica</h4>
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
        </div>

                -->

    </main>

    <!--  
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

    -->

</body>

</html>