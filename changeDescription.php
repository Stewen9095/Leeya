<?php

session_start();

require_once 'auth_functions.php';
require_once 'database.php';

if (isset($_SESSION['user_id'])) {
    if (!empty($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
        header('Location: adminpanel.php');
        exit();
    }
} else {
    header('Location: index.php');
    exit();
}

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

$message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
$error = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';

if (isset($_SESSION['success_message'])) {
    unset($_SESSION['success_message']);
}
if (isset($_SESSION['error_message'])) {
    unset($_SESSION['error_message']);
}

$current_description = htmlspecialchars(explode(' ', $_SESSION['user_description'])[0]);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $is_logged_in) {
    $new_description = $_POST['new_description'] ?? '';

    if (empty($new_description)) {
        $error = 'Completa todos los campos.';
    } else {
        $result = changeUserDescription($_SESSION['user_id'], $new_description);
        $_SESSION['user_description'] = $new_description;
        if ($result['success']) {
            $_SESSION['success_message'] = $result['message'];
        } else {
            $error = $result['message'];
        }
    }

    header('Location: changeDescription.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Cambiar descripcion</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=Poppins:wght@700&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="style.css" />
    <link rel="icon" type="image/png" href="img/logoblanco.png">

    <style>
        html {
            margin: 0;
            padding: 0;
            background-color: white;
        }

        body {
            margin: 0 auto;
            padding: 0;
            font-family: 'HovesDemiBold';
            align-items: center;
            justify-content: center;
        }

        main {
            max-width: 1440px;
            min-width: 200px;
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
            opacity: 55%;
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
    </style>

</head>

<body>

    <img src="img/background2.png" class="background">


    <main>

        <style>
            .getback {
                display: flex;
                width: 42%;
                flex-direction: row;
                flex-wrap: nowrap;
                align-items: center;
                justify-content: space-between;
                margin: 0 auto;
                padding: 3.2% 0 3.2% 0;
            }

            .getbackson1 {
                width: 45%;
                margin: 0 auto;
            }

            .getbackson2 {
                width: 60%;
                margin: 0 auto;
                justify-content: flex-start;
            }

            .getbackson {
                width: 95%;
                display: flex;
                flex-direction: row;
                flex-wrap: nowrap;
                color: white;
                text-decoration: none;
                font-size: 24px;
                align-items: center;
                justify-content: flex-start;
                padding: 0 0 0 0;
                margin: 0;
                transition: 5s;
                color: #333333;
                box-sizing: border-box;
            }

            .getbackson:hover {
                color: #292929cc
            }

            @media (max-width: 750px) {

                .getback {
                    padding: 0;
                    display: flex;
                    flex-direction: column;
                    flex-wrap: nowrap;
                    width: 95%;
                }

                .getbackson {
                    justify-content: center;
                    font-size: 15px;
                    padding: 1.5rem 0 1.5rem 0;
                    margin: 0;
                }

                .getbackson1 {
                    width: 90%;
                }

                .getbackson2 {
                    width: 90%;
                    font-size: 10px;
                }

            }
        </style>


        <div class="getback">

            <div class="getbackson1">
                <a href="user.php" class="getbackson">
                    <svg width="25" height="25" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Volver a mi perfil
                </a>
            </div>

            <div class="getbackson2">
                <?php if ($message): ?>
                    <div class="success-message">
                        <?= htmlspecialchars($message) ?>
                    </div>
                <?php endif; ?>
                <?php if ($error): ?>
                    <div class="error-message">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>
            </div>

        </div>

        <style>
            .auth-container {
                width: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
                text-align: center;
            }

            .auth-card {
                box-sizing: border-box;
                background-color: #64646402;
                border-radius: .8rem;
                border: 1px solid rgba(99, 99, 99, 0.66);
                backdrop-filter: blur(38px);
                width: 60%;
                padding: 2.5rem 3rem 3.5rem 3rem;
            }

            .auth-header {
                width: 85%;
                display: flex;
                flex-direction: column;
                flex-wrap: nowrap;
                align-items: center;
                justify-content: space-between;
                margin: 0 auto 1.5rem auto;

                p {
                    margin: 0;
                    padding: 0;
                    display: block;
                    font-size: 16px;
                    color: #333333;
                }

                h1 {
                    margin: 0;
                    padding: 0;
                    font-size: 26px;
                    color: #333333;
                }
            }

            .formulario {
                width: 72%;
                display: flex;
                flex-direction: column;
                flex-wrap: nowrap;
                justify-content: center;
                align-items: center;
                box-sizing: border-box;
                margin: 0 auto;
                gap: 16px;
            }

            .form-group {
                width: 100%;
                display: flex;
                flex-direction: column;
                flex-wrap: nowrap;
                align-items: center;
                justify-content: start;
                text-overflow: ellipsis;
                height: auto;
                padding-top: clamp(.2rem, .5vh, 2.2rem);
                max-height: 120px;

                box-sizing: border-box;

                label {
                    text-align: start;
                    align-self: flex-start;
                    color: #303030;
                    margin: 0 0 5px 10px;
                    text-overflow: ellipsis;
                    overflow: auto;
                }
            }

            .password-container {
                position: relative;
                width: 100%;
            }

            .form-control {
                width: 96%;
                height: 35px;
                border: 1px solid rgba(99, 99, 99, 0.71);
                border-radius: 10px;
                background-color: #ffffffbb;
                backdrop-filter: blur(12px);
                padding-right: 40px;
                box-sizing: border-box;
                padding: 0 2rem 0 1rem;
                font-family: 'HovesDemiBold';
                color: #333333;
            }

            .error-message {
                background: rgba(255, 238, 238, 0.64);
                color: #c53030af;
                backdrop-filter: blur(5px);
                padding: 0.2rem 1.5rem 0.2rem 1.5rem;
                box-sizing: border-box;
                border-radius: 8px;
                font-size: 0.9rem;
                border: 1px solid #fed7d7;
            }

            .success-message {
                background: rgba(200, 215, 255, 0.64);
                color: #0819b6af;
                backdrop-filter: blur(5px);
                padding: 0.2rem 1.5rem 0.2rem 1.5rem;
                box-sizing: border-box;
                border-radius: 8px;
                font-size: 0.9rem;
                border: 1px solid #d3dbff;
            }

            .auth-button {
                width: 58%;
                background-color: #ffffff57;
                backdrop-filter: blur(5px);
                padding: 2%;
                border: none;
                border: 1px solid rgba(99, 99, 99, 0.71);
                border-radius: 10px;
                margin-top: 5%;
                color: #333333;
                font-family: "HovesDemiBold";
                font-size: 16px;
                cursor: pointer;
            }

            @media (max-width: 750px) {
                .auth-card {
                    box-sizing: border-box;
                    background-color: #64646402;
                    border-radius: 10px;
                    border: .8px solid rgba(99, 99, 99, 0.66);
                    backdrop-filter: blur(80px);
                    width: 88%;
                    padding: 2.2rem 1rem 3rem 1rem;
                }

                .auth-header {
                    width: 90%;
                    display: flex;
                    flex-direction: column;
                    flex-wrap: nowrap;
                    align-items: center;
                    justify-content: space-between;
                    margin: 2% auto 5% auto;

                    p {
                        margin: 0;
                        padding: 0;
                        display: block;
                        font-size: 16px;
                        color: #333333;
                    }

                    h1 {
                        margin: 0;
                        padding: 0;
                        font-size: 20px;
                        color: #333333;
                    }
                }

                .formulario {
                    width: 98%;
                    display: flex;
                    flex-direction: column;
                    flex-wrap: nowrap;
                    justify-content: center;
                    align-items: center;
                    box-sizing: border-box;
                    margin: 0 auto;
                    gap: 25px;
                }

                .form-group {
                    width: 100%;
                    display: flex;
                    flex-direction: column;
                    flex-wrap: nowrap;
                    align-items: center;
                    justify-content: center;

                    label {
                        text-align: start;
                        color: #303030;
                        margin: 0 0 5px 10px;
                        font-size: 14px;
                    }
                }

                .error-message {
                    background: rgba(255, 238, 238, 0.64);
                    color: #c53030af;
                    backdrop-filter: blur(5px);
                    padding: 0.2rem 1.5rem 0.2rem 1.5rem;
                    box-sizing: border-box;
                    border-radius: 8px;
                    font-size: 0.9rem;
                    border: 1px solid #fed7d7;
                    text-align: center;
                }

                .success-message {
                    background: rgba(200, 215, 255, 0.64);
                    color: #0819b6af;
                    backdrop-filter: blur(5px);
                    padding: 0.2rem 1.5rem 0.2rem 1.5rem;
                    box-sizing: border-box;
                    border-radius: 8px;
                    font-size: 0.9rem;
                    border: 1px solid #d3dbff;
                    text-align: center;
                }

                .auth-button {
                    width: 90%;
                    background-color: #ffffff57;
                    backdrop-filter: blur(5px);
                    padding: 2%;
                    border: none;
                    border: 1px solid rgba(99, 99, 99, 0.71);
                    border-radius: 10px;
                    margin-top: 4%;
                    color: #333333;
                    font-family: "HovesDemiBold";
                    font-size: 20px;
                    cursor: pointer;
                }
            }
        </style>


        <div class="auth-container">
            <div class="auth-card">
                <div class="auth-header">
                    <h1 class="titulo">Modifica tu descripcion</h1>
                    <p>Cuéntanos más de ti</p>
                </div>
                <form method="post" autocomplete="off">
                    <div class="form-group">
                        <label class="infouser">Tu descripcion actual es: <?php
                        if ($current_description == '') {
                            ?>
                                <p>Aun no cuentas con una descripcion</p>
                                <?php
                        } else {
                            ?>
                                <?php echo htmlspecialchars($_SESSION['user_description']); ?>
                                <?php
                        }
                        ?>
                        </label>
                    </div>
                    <br>
                    <div class="form-group">
                        <label for="new_password">Nueva descripcion</label>
                        <input type="text" id="new_description" name="new_description" class="form-control" required>
                    </div>


                    <button type="submit" class="auth-button">Cambiar descripcion</button>

                </form>
            </div>
        </div>

    </main>

</body>

</html>