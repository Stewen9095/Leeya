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
        html {
            background: white;
            margin: 0;
            padding: 0;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'HovesDemiBold';
            background: white;
        }

        nav {
            max-width: 1440px;
            min-width: 200px;
            width: 96%;
            height: auto;
            background-color: #cfcfcf67;
            display: flex;
            justify-content: center;
            align-items: stretch;
            box-sizing: border-box;
            margin: 0 auto;
            border: 1px solid rgba(168, 168, 168, 0.37);
            border-radius: 1rem;
            margin-top: 15px;
            gap: .1%;
            font-size: 14px;
        }

        nav a {
            box-sizing: border-box;
            padding: .2rem 1rem;
            margin: .3rem;
            border: 1px solid rgba(168, 168, 168, 0.37);
            background-color: #a5a5a567;
            border-radius: .6rem;
            color: #272727;
            text-decoration: none;
            min-width: 0;
            overflow: hidden;

            h3 {
                box-sizing: border-box;
                margin: 0;
                padding: 0;
            }
        }

        /* Cel */
        @media (max-width: 500px) {

            nav {
                margin-top: 6px;
                flex-direction: column;
                font-size: 8px;
                border-radius: 5px;
                padding: 2px 0;
                width: 96%;

                a {
                    margin: .1rem;
                    padding: 2px 10px;
                    width: 98%;
                    border-radius: 5px;
                    display: flex;
                    justify-content: center;
                }

            }


        }
    </style>

</head>

<body>

    <nav>

        <a href="index.php" class="image-logo">
            <h3>LEEYA</h3>
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

        <?php elseif (!$is_logged_in): ?>

            <a href="login.php">
                <h3>INICIAR SESIÓN</h3>
            </a>

        <?php endif; ?>

        <?php if ($is_logged_in): ?>


            <a class="circle1" href="myproposals.php">
<!--                 <img src="img/noti.png" alt="Notificación" class="noti-icon">
                <?php if ($badge_text): ?>
                    <span class="numnoti">
                        <p><?= $badge_text ?></p>
                    </span>
                <?php endif; ?> -->
            </a>

            <a class="circle2" href="user.php">
                <!-- <img src="img/user.png" alt="Usuario" class="user"> --> 
            </a>


        <?php endif; ?>

    </nav>


    <main>

        <!--  

        <div class="panel1">
            <div class="son1">
                <p>LIBROS AL ALCANCE DE TODOS: PUBLICA O ADQUIERE TUS LIBROS FAVORITOS HOY DESDE
                    LA COMODIDAD DE TU HOGAR</p>
            </div>
            <div class="son1">
                <img src="img/libros.png" alt="Libros">
            </div>
        </div>

        -->

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