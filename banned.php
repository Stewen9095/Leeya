<?php

session_start();

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
        $user_role = $_SESSION['user_role'] ?? '';
    }

}

if (!$is_logged_in) {
    header('Location: index.php');
    exit();
}

if ($user_role === 'admin') {
    header('Location: adminpanel.php');
    exit();
} elseif ($user_role === 'user') {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    logoutUser();
    header('Location: index.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuenta Banneada</title>
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
            background: beige;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .ban-container {
            background-color: #64646425;
            backdrop-filter: blur(8px);
            border: 1px solid rgba(99, 99, 99, 0.37);
            border-radius: 10px;
            padding: 2rem;
            max-width: 600px;
            text-align: center;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.12);
        }

        .ban-container h1 {
            margin-top: 0;
            color: #333333;
        }

        .ban-container p {
            color: #333333;
            font-size: 1.1rem;
            line-height: 1.6;
        }

        .ban-container .logout-btn {
            margin-top: 2rem;
        }

        .ban-container form {
            margin: 0;
        }

        .ban-container button {
            background-color: #d8d8d888;
            color: #333333;
            font-family: 'HovesDemiBold';
            backdrop-filter: blur(5px);
            border: 1px solid rgba(99, 99, 99, 0.37);
            border-radius: clamp(.4rem, 4vh,1rem);
            padding: 0.8rem 2rem;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition:3s;
        }

        .ban-container button:hover {
            background-color: #80808088;
        }

        @media (max-width: 750px) {
            .ban-container {
                margin: 1rem;
                padding: 1.5rem;
            }

            .ban-container h1 {
                font-size: 1.5rem;
            }

            .ban-container p {
                font-size: 1rem;
            }
        }
    </style>
</head>

<body>
    <div class="ban-container">
        <h1>⛔ Cuenta Banneada</h1>
        <p>Lo sentimos, tu cuenta ha sido suspendida y no tienes acceso a la plataforma.</p>
        <p>Si crees que esto es un error, por favor contáctanos a través del correo de soporte.</p>
        
        <div class="logout-btn">
            <form method="post">
                <input type="hidden" name="logout" value="1">
                <button type="submit">Cerrar Sesión</button>
            </form>
        </div>
    </div>
</body>

</html>