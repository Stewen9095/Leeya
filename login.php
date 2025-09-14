<?php
session_start();

require_once 'auth_functions.php';
require_once 'database.php';

$error = '';

if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
    $_SESSION['last_attempt_time'] = time();
}

$bloqueado = false;
$tiempo_bloqueo = 60; // 1 minuto
$max_intentos = 10;

if ($_SESSION['login_attempts'] >= $max_intentos) {
    $elapsed = time() - $_SESSION['last_attempt_time'];
    if ($elapsed < $tiempo_bloqueo) {
        $bloqueado = true;
        $tiempo_restante = $tiempo_bloqueo - $elapsed;
        $error = 'Demasiados intentos fallidos. Por favor, espera ' . $tiempo_restante . ' segundos.';
    } else {
        $_SESSION['login_attempts'] = 0;
        $_SESSION['last_attempt_time'] = time();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($bloqueado) {
        $error = 'Demasiados intentos fallidos. Intenta de nuevo en ' . ($tiempo_bloqueo - (time() - $_SESSION['last_attempt_time'])) . ' segundos.';
    } elseif (empty($email) || empty($password)) {
        $error = 'Por favor completa todos los campos.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'El formato del email no es válido.';
    } elseif (!userExists($email)) {
        $error = 'No existe una cuenta con este email.';
    } else {
        $result = loginUser($email, $password);

        if ($result['success']) {
            $_SESSION['login_attempts'] = 0;
            $_SESSION['last_attempt_time'] = time();

            if (isset($result['user'])) {
                $_SESSION['user_id'] = $result['user']['id'];
                $_SESSION['user_name'] = $result['user']['name'];
                $_SESSION['user_email'] = $result['user']['email'];
                $_SESSION['user_role'] = $result['user']['role'];

                if ($result['user']['role'] === 'admin') {
                    header('Location: adminpanel.php');
                    exit();
                } else {
                    header('Location: index.php');
                    exit();
                }
            }
        } else {
            $_SESSION['login_attempts']++;
            $_SESSION['last_attempt_time'] = time();
            $error = $result['message'] ?? 'Credenciales incorrectas.';
        }
    }
}

// Si ya hay sesión activa, redirigir según rol
if (isset($_SESSION['user_id'])) {
    if (!empty($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
        header('Location: adminpanel.php');
        exit();
    } else {
        header('Location: index.php');
        exit();
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de sesión</title>
    <link rel="icon" href="img/icon.png">
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <img src="img/background2.png" class="background">

    <a href="index.php" class="back-home">
        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        Volver al inicio
    </a>

    <div class="auth-container">

        <div class="auth-card">
            <div class="auth-header">
                <h1>Inicio de sesión</h1>
                <p>Accede a tu cuenta de Leeya</p>
            </div>

            <?php if (!empty($_SESSION['message'])): ?>
                <div class="success-message">
                    <?php echo htmlspecialchars($_SESSION['message']); ?>
                </div>
                <?php unset($_SESSION['message']); ?>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="error-message">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>

                <button type="submit" class="auth-button">Iniciar sesión</button>
            </form>

            <div class="auth-links">
                <p>¿No tienes cuenta? <a href="signup.php">Registrate aquí</a></p>
            </div>
        </div>
    </div>

    <style>
        body,
        html {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: 'HovesDemiBold';
            overflow: auto;
        }

        .background {
            position: fixed;
            top: 50%;
            left: 50%;
            width: 100%;
            max-width: 100vw;
            display: block;
            height: 100%;
            object-fit: cover;
            background: black;
            transform: translate(-50%, -50%);
            z-index: -1;

        }

        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0rem 1rem;
            position: relative;
            z-index: 1;
            margin: 0rem;
        }

        .auth-card {
            background: rgba(255, 255, 255, 1);
            border-radius: var(--radius, 1.5rem);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 2rem 3rem;
            width: 100%;
            max-width: 400px;
            text-align: center;
            backdrop-filter: blur(5px);
        }

        .auth-header h1 {
            font-family: 'HovesBold';
            font-size: 1.8rem;
            color: #000000;
            margin-bottom: 0.5rem;
        }

        .auth-header p {
            font-size: 1rem;
            font-family: 'HovesDemiBold';
            color: #000000ff
        }

        .form-group {
            margin-bottom: 1.5rem;
            text-align: left;
        }

        .form-group label {
            font-family: 'HovesDemiBold';
            font-size: 1rem;
            color: #000000ff;
            margin-bottom: 0.5rem;
            display: block;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e4e5e6b0;
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
            width: 100%;
            background-color: var(--color-accent, #000080);
            color: #fff;
            padding: 0.875rem;
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

        .auth-links p {
            font-family: 'HovesDemiBold';
            font-size: 1rem;
            margin-bottom: 1.5rem;
        }

        .auth-links a {
            color: #1a1a1aff;
            font-weight: 600;
            transition: background-color 0.5s, transform 0.5s;
            text-decoration: none;
        }

        .auth-links a:hover {
            text-decoration: underline;
            color: #000000;
            transition: transform 0.5s;
        }

        .back-home {
            position: absolute;
            top: 2rem;
            left: 2rem;
            color: white;
            font-family: 'HovesExpandedBold';
            font-size: 1.4rem;
            font-weight: 600;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            z-index: 2;
        }

        .back-home:hover {
            color: var(--color-text-muted, #ccc);
        }

        @media (max-width: 480px) {
            .auth-card {
                padding: 2rem 1.5rem;
                margin: 1rem;
            }

            .back-home {
                position: relative;
                top: auto;
                left: auto;
                margin-bottom: 1rem;
                justify-content: center;
            }
        }
    </style>

</body>

</html>