<?php

session_start();

require_once 'auth_functions.php';
require_once 'database.php';

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

if (isset($_SESSION['user_id'])) {
    if (!empty($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
        header('Location: adminpanel.php');
        exit();
    }
} else {
    header('Location: index.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publicar libro</title>
    <link rel="icon" href="img/icon.png">
    </link>
    <link rel="stylesheet" href="style.css">
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

    <style>
        body {
            background: #000;
            color: #fff;
            font-family: 'HovesDemiBold';
            margin: 0;
        }

        html {
            font-size: 15px;
        }

        .form-whole {
            max-width: 82%;
            margin: 1.5rem auto;
            height: 27.8rem;
            max-height: 27.8rem;
            margin-top: -1.2rem;
            background: linear-gradient(to bottom,
                    #001aafff 0%,
                    #000080 55%);
            border-radius: 2rem;
            box-shadow: 0 0 0.5rem rgba(240, 240, 240, 0.05);
            padding: 1.5rem 1.8rem 1.5rem 1.8rem;
            display: flex;
            flex-direction: row;
            align-items: center;
            gap: 1.8rem;
        }

        .bookinfo {
            text-align: center;
            width: 65%;
            height: 96%;
            /*background-color: #fff;*/
            border-radius: 2rem;
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .book-form {
            width: 90%;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.2rem 0.8rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            text-align: left;
        }

        .form-group label {
            font-weight: 600;
            font-family: "HovesExpandedBoldItalic";
            color: #000080;
            font-size: clamp(0.75rem, 1.5vw, 0.95rem);
            margin-bottom: 0.3rem;
        }

        .form-group input {
            padding: 0.6rem 0.8rem;
            border-radius: 1rem;
            border: 1.8px solid #001aaf;
            font-size: clamp(0.8rem, 1.6vw, 1rem);
            outline: none;
            transition: all 0.25s ease;
        }

        .form-group input:focus {
            border-color: #0000ff;
            box-shadow: 0 0 0.3rem rgba(0, 0, 255, 0.3);
        }

        .form-buttons {
            grid-column: span 2;
            /* ocupa todo el ancho */
            display: flex;
            justify-content: center;
            gap: 1.2rem;
            margin-top: 0.8rem;
        }

        .form-buttons button {
            padding: 0.6rem 1.4rem;
            border-radius: 1rem;
            font-family: "HovesExpandedBoldItalic";
            font-size: clamp(0.8rem, 1.5vw, 1rem);
            cursor: pointer;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-save {
            background: linear-gradient(to bottom, #001aafff 0%, #000080 95%);
            color: white;
        }

        .btn-cancel {
            background: #ddd;
            color: #000;
        }

        .btn-save:hover {
            background: linear-gradient(to bottom, #000080 0%, #001aafff 95%);
        }

        .btn-cancel:hover {
            background: #ccc;
        }

        .bookpic {
            text-align: center;
            width: 35%;
            height: 96%;
            background-color: #fff;
            border-radius: 2rem;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            position: relative;
        }

        .preview {
            width: 100%;
            height: 28%;
            margin-top: 0%;
            background: linear-gradient(to bottom,
                    #000080 0%,
                    #001aafff 55%);
            border-radius: 1rem;
            align-items: center;
            position: absolute;
        }

        .preview-text {
            width: 100%;
            height: 100%;
            /*background-color: blue;*/
            border-radius: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 0.01rem;
            align-items: center;
        }

        .preview-text p {
            font-family: "HovesExpandedBoldItalic";
            max-width: 95%;
            max-height: 95%;
            margin: -0.2rem;
            padding: 0;
            font-size: clamp(0.2rem, 2vw, 0.92rem);
            color: white;
            align-items: center;
        }

        .realpic {
            width: 100%;
            height: 100%;
            margin-top: 0%;
            background: yellow;
            border-radius: 1.2rem;
            align-items: center;
            overflow: hidden;
        }

        .realpic img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            border-radius: inherit;
            max-width: 100%;
            max-height: 100%;
            image-rendering: auto;
        }

        .buttons-back {
            width: 100%;
            height: 22%;
            margin-top: 0%;
            background: linear-gradient(to bottom,
                    #000080 0%,
                    #001aafff 55%);
            border-radius: 1.2rem;
            align-items: center;
            position: absolute;
            bottom: 0;
        }
    </style>

    <div class="form-whole">

        <div class="bookinfo"> <!-- Caja izquierda -->
            <form class="book-form">
                <div class="form-group">
                    <label for="name">Título del libro</label>
                    <input type="text" id="name" name="name" placeholder="Ej: Cien años de soledad" required>
                </div>

                <div class="form-group">
                    <label for="author">Autor</label>
                    <input type="text" id="author" name="author" placeholder="Ej: Gabriel García Márquez" required>
                </div>

                <div class="form-group">
                    <label for="genre">Género</label>
                    <input type="text" id="genre" name="genre" placeholder="Ej: Realismo mágico" required>
                </div>

                <div class="form-group">
                    <label for="editorial">Editorial</label>
                    <input type="text" id="editorial" name="editorial" placeholder="Ej: Sudamericana" required>
                </div>

                <div class="form-buttons">
                    <button type="submit" class="btn-save">Guardar</button>
                    <button type="reset" class="btn-cancel">Cancelar</button>
                </div>
            </form>
        </div>

        <div class="bookpic"> <!-- Caja derecha -->

            <div class="preview"> <!-- Caja azul -->

                <div class="preview-text"> <!-- Reservada para el texto de preview -->
                    <p>Titulo: 100 anos de sobriedad</p>
                    <p>Autor: Gabriel garcia marquez </p>
                    <p>Estado: 5</p>
                </div>

            </div>

            <div class="realpic"> <!-- Reservada para la imagen del libro -->
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSgVfHORQFLyUf_rNove-xUmxIskDeMJ63REz_YIMQ6S0vCyQdkBvJos4igKspvCgpqnpy8h0xM--1uckzZIxDgyoHy37-MowkF-YzvVx8"
                    alt="Imagen del libro">
            </div>

            <div class="buttons-back">

            </div>

        </div>

    </div>

</body>

</html>