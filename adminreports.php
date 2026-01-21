<?php

session_start();

require_once 'auth_functions.php';
require_once 'database.php';

$is_logged_in = false;
$user_role = '';
$pdo = getDBConnection();


refreshSessionUser();
updateExpiredAuctions();

if (isLoggedIn()) {

    if (isset($_SESSION['user_id'])) {
        $is_logged_in = true;
        $user_name = htmlspecialchars($_SESSION['user_name'] ?? '');
        $user_role = htmlspecialchars($_SESSION['user_role'] ?? 'admin');
    }

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
</head>
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
<body>
    <header>
        <nav>
            <a href="adminpanel.php">
                <img src="img/icono.png" alt="Icono" class="iconoimg">
            </a>
            <div class="nav-btns">
                <a href="adminpanel.php">
                    <h3>EXPLORAR</h3>
                </a>
                <a href="userlist.php">
                    <h3>USUARIOS</h3>
                </a>
                <a href="logout.php">
                    <h3>CERRAR SESION</h3>
                </a>
            </div>
        </nav>
    </header>
    <main style="min-height:70vh;display:flex;align-items:center;justify-content:center;">
        <h1 style="color:#fff;font-family:'HovesExpandedBold';font-size:2rem;">Reportes de Administrador</h1>
    </main>
</body>
</html>