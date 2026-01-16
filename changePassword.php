<?php

session_start();

require_once 'auth_functions.php';
require_once 'database.php';

$is_logged_in = isset($_SESSION['user_id']);
$user_email = $is_logged_in ? htmlspecialchars($_SESSION['user_email']) : '';
$user_first_name = $is_logged_in && isset($_SESSION['user_name']) ? htmlspecialchars(explode(' ', $_SESSION['user_name'])[0]) : '';

$is_logged_in = false;
$user_role = '';

$message = '';
$error = '';

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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $is_logged_in) {
    $current = $_POST['current_password'] ?? '';
    $new = $_POST['new_password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if (empty($current) || empty($new) || empty($confirm)) {
        $error = 'Completa todos los campos.';
    } elseif ($new !== $confirm) {
        $error = 'Las contraseñas nuevas no coinciden.';
    } else {
        // Verifica la contraseña actual
        $pdo = getDBConnection();
        $stmt = $pdo->prepare("SELECT passwd FROM user WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || !password_verify($current, $user['passwd'])) {
            $error = 'La contraseña actual es incorrecta.';
        } else {
            $result = changeUserPassword($_SESSION['user_id'], $new);
            if ($result['success']) {
                $message = $result['message'];
                header('Location: changePassword.php');
            } else {
                $error = $result['message'];
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Cambio de contraseña</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=Poppins:wght@700&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="style.css" />
    <link rel="icon" href="img/icon.png" type="image/png">

    <style>
        html {
            margin: 0;
            padding: 0;

        }

        body {
            margin: 0 auto;
            padding: 0;
            font-family: 'HovesDemiBold';
            align-items: center;
            justify-content: center;
            max-width: 1440px;
            min-width: 200px;
            width: 100%;
        }

        main {
            width: 100%;
            height: auto;
            display: flex;
            flex-direction: column;
            flex-wrap: nowrap;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            position: relative;
        }

        .background {
            position: fixed;
            width: 100%;
            max-width: 100dvw;
            height: auto;
        }

        @media (max-width: 750px) {
            .auth-card {
                padding: 2rem 1.5rem;
                margin: 1rem;
            }

            .getbackson {
                position: relative;
                top: auto;
                left: auto;
                margin-bottom: 1rem;
                justify-content: center;
            }
        }

        .auth-container {
            margin: 0 auto;
        }


        .form-control {
            width: 100%;
            padding: 0.7rem;
            border: 2px solid #a1a1a1b0;
            border-radius: var(--radius, 8px);
            font-size: 1rem;
            box-sizing: border-box;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--color-primary, #333);
        }

        .error-message {
            background: #fee;
            color: #c53030;
            padding: 0.75rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            font-size: 0.9rem;
            border: 1px solid #fed7d7;
        }

        .success-message {
            background: #f0fff4;
            color: #38a169;
            padding: 0.75rem;
            border-radius: var(--radius);
            margin-bottom: 1rem;
            font-size: 0.9rem;
            border: 1px solid #c6f6d5;
        }

        .auth-button {
            width: 65%;
            background-color: var(--color-accent, #000080);
            color: #fff;
            padding: 0.85rem;
            border: none;
            border-radius: var(--radius, 8px);
            font-size: 1.05rem;
            font-weight: 600;
            font-family: 'HovesExpandedDemiBold';
            cursor: pointer;
            transition: background-color 0.5s, transform 0.5s;
        }

        .auth-button:hover {
            background-color: var(--color-accent-hover, #ffffffff);
            color: #000000ff;
            transform: translateY(-0.1px);
            box-shadow: 1px 2px 2px rgba(0, 0, 0, 0.22);
        }

        .auth-button2 {
            margin-top: 0.6rem;
            width: 65%;
            background-color: var(--color-accent, #000080);
            color: #fff;
            padding: 0.85rem;
            border: none;
            border-radius: var(--radius, 8px);
            font-size: 1.05rem;
            font-weight: 600;
            font-family: 'HovesExpandedDemiBold';
            cursor: pointer;
            transition: background-color 0.5s, transform 0.5s;
        }

        .auth-button2:hover {
            background-color: var(--color-accent-hover, #ffffffff);
            color: #000000ff;
            transform: translateY(-0.1px);
            box-shadow: 1px 2px 2px rgba(0, 0, 0, 0.22);
        }

        .titulo {
            margin-top: 0rem;
        }
    </style>
</head>

<body>

    <!-- <img src="img/background2.png" class="background"> -->

    <main>

        <style>
            .getback {
                display: flex;
                width: 100%;
                flex-direction: column;
                flex-wrap: nowrap;
                align-items: center;
                justify-content: center;
                margin: 0 auto;
                padding: 0 0 1.8rem 0;
            }

            .getbackson {
                width: 95%;
                display: flex;
                flex-direction: row;
                flex-wrap: nowrap;
                color: white;
                text-decoration: none;
                font-size: 25px;
                align-items: center;
                justify-content: flex-start;
                padding: 2rem 0 0 0;
                transition: 3s;
            }


            .getbackson:hover {
                color: orange
            }


            @media (max-width: 750px) {

                .getbackson {
                    margin-bottom: 1rem;
                    justify-content: center;
                    font-size: 20px;
                    padding: 2rem;
                }
            }
        </style>

        <div class="getback">
            <a href="user.php" class="getbackson">
                <svg width="25" height="25" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Volver a mi perfil
            </a>
        </div>



        <style>
            .auth-container {
                background-color: red;
                width: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
                text-align: center;
            }

            .auth-card {
                background-color: purple;
                width: 45%;
                backdrop-filter: blur(5px);
                padding: 1rem 3rem;
            }

            .auth-header {
                width: 100%;
                display: flex;
                flex-direction: column;
                flex-wrap: nowrap;
                align-items: center;
                justify-content: space-between;
                margin: 0 auto 1rem auto;
                gap: .6rem;

                p {
                    margin: 0;
                    padding: 0;
                    display: block;
                    font-size: 16px;
                }

                h1 {
                    margin: 0;
                    padding: 0;
                    font-size: 32px;
                }

            }
        </style>


        <div class="auth-container">

            <div class="auth-card">

                <div class="auth-header">
                    <h1 class="titulo">Cambiar contraseña</h1>
                    <p>Actualiza la contraseña de tu cuenta Leeya</p>
                </div>

                <div class="messages">
                <?php if ($message): ?>
                    <div class="success-message"><?= htmlspecialchars($message) ?></div>
                <?php endif; ?>
                <?php if ($error): ?>
                    <div class="error-message"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                </div>

                <form method="post" autocomplete="off" class="formulario">
                    <div class="form-group">
                        <label for="current_password">Contraseña actual</label>
                        <input type="password" id="current_password" name="current_password" class="form-control"
                            required>
                    </div>

                    <div class="form-group">
                        <label for="new_password">Nueva contraseña</label>
                        <input type="password" id="new_password" name="new_password" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="confirm_password">Confirmar nueva contraseña</label>
                        <input type="password" id="confirm_password" name="confirm_password" class="form-control"
                            required>
                    </div>

                    <button type="submit" class="auth-button">Cambiar contraseña</button>

                </form>
                <a href="user.php"><button class="auth-button2">Volver</button></a>
            </div>
        </div>

    </main>

</body>

</html>