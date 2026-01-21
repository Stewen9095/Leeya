<?php

session_start();

// Prevenir caché para que no se pueda ir atrás después de cerrar sesión
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

require_once 'auth_functions.php';
require_once 'database.php';

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

// Validar que solo admins puedan acceder
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: index.php');
    exit();
}

if (isset($_SESSION['user_id'])) {
    if (!empty($_SESSION['user_role']) && $_SESSION['user_role'] === 'user') {
        header('Location: index.php');
        exit();
    } elseif ($_SESSION['user_role'] === 'banned') {
        header('Location: banned.php');
        exit();
    }
}

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
            position: fixed;
            max-width: 1440px;
            min-width: 200px;
            width: fit-content;
            height: auto;
            background-color: #64646425;
            backdrop-filter: blur(8px);
            display: inline-flex;
            justify-content: center;
            align-items: stretch;
            box-sizing: border-box;
            left: 0;
            right: 0;
            margin: auto;
            border: 1px solid rgba(99, 99, 99, 0.37);
            border-radius: 1rem;
            font-size: 15px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            z-index: 5;
        }

        nav a {
            box-sizing: border-box;
            margin-inline: auto;
            inset-inline: 0;
            width: fit-content;
            padding: .2rem .5rem;
            margin: .3rem .3rem .3rem .3rem;
            border: 1px solid rgba(99, 99, 99, 0.37);
            backdrop-filter: blur(5px);
            background-color: #d8d8d888;
            border-radius: .6rem;
            color: #333333;
            text-decoration: none;
            min-width: 140px;
            overflow: hidden;
            max-width: 18%;
            max-height: 30px;

            .content {
                box-sizing: border-box;
                margin: 0;
                padding: 0;
                text-align: center;
            }

        }

        /* Cel */
        @media (max-width: 750px) {

            nav {
                position: static;
                display: flex;
                margin-top: 30px;
                flex-direction: column;
                font-size: 13px;
                border-radius: 5px;
                padding: 2px 0;
                width: 80%;
                align-items: center;

                a {
                    margin: .1rem;
                    padding: 2px 10px;
                    width: 98%;
                    height: 35px;
                    border-radius: 5px;
                    display: flex;
                    justify-content: center;
                    align-items: stretch;
                    max-width: 100%;
                    min-height: 30px;
                }

            }

        }
    </style>
</head>


<body>

    <nav>

        <a href="index.php" class="image-logo">
            <div class="content">LEEYA</div>
        </a>
        <a href="explore.php" class="image-logo">
            <div class="content">EXPLORAR</div>
        </a>
        <a href="userlist.php" class="image-logo">
            <div class="content">USUARIOS</div>
        </a>
        <a href="reports.php" class="image-logo">
            <div class="content">REPORTES</div>
        </a>
        <a href="logout.php" class="image-logo">
            <div class="content">CERRAR SESIÓN</div>
        </a>

    </nav>


    <main>

        <style>
            main {
                max-width: 1440px;
                min-width: 200px;
                width: 92%;
                height: auto;
                display: flex;
                flex-direction: column;
                margin: 2.8rem auto 0 auto;
                padding: 2rem 0 0 0;
                justify-content: center;
                align-items: center;
            }

            .panel1 {
                width: 82%;
                height: 150px;
                margin-top: 50px;
                display: flex;
                flex-wrap: wrap;
                align-items: stretch;
                justify-content: center;
            }

            .son11 {
                flex: 1 0 55%;
                max-width: 60%;
                height: 100%;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                color: #333333;
                align-self: center;
                justify-self: center;
                gap: 0;
                overflow: hidden;

                p:first-child {
                    display: flex;
                    justify-content: center;
                    align-items: flex-end;
                    width: 100%;
                    font-size: 26px;
                    text-align: center;
                    margin: auto;
                    height: 42%;
                    padding: 0;
                    box-sizing: border-box;
                }

                p:last-child {
                    display: flex;
                    flex-direction: row;
                    text-align: center;
                    width: 100%;
                    font-size: 18px;
                    height: 58%;
                    margin: auto;
                    box-sizing: border-box;
                    padding: 0 6% 0 6%;
                }

            }

            .son12 {
                flex: 1 0 45%;
                max-width: 60%;
                height: 95%;
                background-color: #C0C0C0;
                display: flex;
                justify-content: flex-start;
                align-self: center;
                border: 1px solid #64646467;
                align-items: center;
                border-radius: .6rem;
                align-self: center;
                justify-self: center;
                box-sizing: border-box;

                .imagekid {
                    display: flex;
                    flex-direction: row;
                    justify-content: flex-start;
                    align-items: center;
                    height: 140%;
                    width: auto;
                    margin: 0 6%;
                    background-color: transparent;
                }
            }

            @media(max-width: 750px) {

                main {
                    flex-direction: column;
                    margin: 2rem auto 0 auto;
                    width: 92%;
                    height: auto;
                    padding: 0;
                }

                .panel1 {
                    display: flex;
                    flex-direction: column;
                    flex-wrap: nowrap;
                    gap: 2rem;
                    width: 90%;
                    height: auto;
                    margin: auto;
                    height: auto;
                }

                .son11 {
                    flex: 0;
                    justify-content: center;
                    width: 100%;
                    margin: auto;
                    max-width: 100%;

                    p:first-child {
                        font-size: 18px;
                    }

                    p:last-child {
                        font-size: 14px;
                        padding: 0;
                    }
                }

                .son12 {
                    flex: 0;
                    width: 100%;
                    max-width: 98%;
                    flex-direction: column;
                    border-radius: 5px;
                    padding: 3%;
                    margin: auto;

                    .imagekid {
                        display: flex;
                        flex-direction: row;
                        justify-content: flex-start;
                        align-items: center;
                        height: auto;
                        width: 40%;
                        margin: 0 4%;
                        background: transparent;
                    }
                }
            }
        </style>

        <div class="panel1">
            <div class="son11">
                <p>BIENVENIDO ADMINISTRADOR</p>
                <p>Gestión del espacio #1 de acceso a referencias bibliográficas en línea de la Universidad Distrital
                </p>
            </div>
            <div class="son12">
                <img src="img/libros.png" alt="Libros" class="imagekid">
            </div>
        </div>

        <style>
            .panel2 {
                max-width: 68%;
                width: 100%;
                height: 330px;
                display: flex;
                flex-direction: row;
                flex-wrap: nowrap;
                justify-content: center;
                align-items: center;
                margin: 1% auto 0 auto;
                gap: 3rem;
            }

            .son21 {
                height: 90%;
                width: 48%;
                display: flex;
                flex-direction: row;
                justify-content: center;
                align-items: center;
            }

            .son22 {
                width: 52%;
                height: 80%;
                display: flex;
                justify-content: center;
                align-items: center;

                video {
                    height: 85%;
                    width: auto;
                }
            }


            @media(max-width: 750px) {

                .panel2 {
                    display: flex;
                    flex-wrap: nowrap;
                    flex-direction: column;
                    max-width: 90%;
                    height: auto;
                    margin-top: 15%;
                    margin-bottom: 8%;
                    align-items: center;
                    justify-content: center;
                    gap: 0;
                }

                .son21 {
                    width: 100%;
                    height: 170px;
                    margin: 0 0 3.8rem 0;
                    display: flex;
                    align-items: center;
                    justify-content: center;

                    .twitter-tweet {
                        margin: 0;
                        padding: 0;
                        align-self: center;
                    }
                }

                .son22 {
                    width: 100%;
                    height: auto;
                    justify-content: center;
                    display: flex;
                    flex-direction: row;
                    align-items: center;
                    justify-content: center;

                    video {
                        width: 92%;
                        height: auto;
                    }
                }

            }
        </style>

        <div class="panel2">
            <div class="son21">
                <blockquote class="twitter-tweet">
                    <p lang="en" dir="ltr">Get any book with Leeya!</p>&mdash; readleeya (@readleeya) <a
                        href="https://twitter.com/readleeya/status/1945965007847477482?ref_src=twsrc%5Etfw">July 17,
                        2025</a>
                </blockquote>
                <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
            </div>

            <div class="son22">
                <video autoplay muted loop playsinline preload="auto" poster="poster.jpg">
                    <source src="vid/udbiblio.mp4" type="video/mp4">
                    Tu navegador no soporta el video HTML5.
                </video>
            </div>

        </div>

    </main>

</body>

</html>