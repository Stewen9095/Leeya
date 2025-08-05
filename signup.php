<?php

session_start(); // Inicia la sesión al principio de tu script

require_once 'auth_functions.php'; // Asegúrate de que isLoggedIn() y registerUser() estén aquí.

// --- Redirección si el usuario ya está logueado ---
// Si un usuario ya tiene una sesión activa (sea de usuario normal o de organizador/admin),
// lo redirigimos para evitar que intente registrarse de nuevo.
if (isset($_SESSION['user_id']) || isset($_SESSION['org_id'])) {
    header('Location: index.php'); // Redirige a la página principal
    exit(); // Crucial para detener la ejecución del script después de la redirección.
}

$error = '';    // Para mensajes de error
$success = '';  // Para mensajes de éxito

// --- Procesamiento del Formulario de Registro ---
// Solo procesa el formulario si la solicitud es de tipo POST.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recopila y sanitiza los datos del formulario.
    // Usamos el operador de fusión de null (??) para evitar errores si la clave POST no existe.
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // --- Validaciones de Entrada ---
    if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = 'Por favor, completa todos los campos.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'El formato del correo electrónico no es válido.';
    } elseif (strlen($password) < 6) {
        $error = 'La contraseña debe tener al menos 6 caracteres.';
    } elseif ($password !== $confirm_password) {
        $error = 'Las contraseñas no coinciden.';
    } else {
        // --- Intentar Registrar al Usuario ---
        // Llama a la función 'registerUser()' que debe estar definida en 'auth_functions.php'.
        // Se espera que esta función devuelva un array con 'success' (booleano) y 'message' (string).
        $result = registerUser($name, $email, $password);

        if ($result['success']) {
            // Si el registro fue exitoso, muestra un mensaje de éxito.
            // Considera usar mensajes flash de sesión aquí si rediriges inmediatamente.
            $_SESSION['message'] = '¡Cuenta creada exitosamente! Ahora puedes iniciar sesión.';
            header('Location: login.php'); // Redirige al usuario directamente a la página de login.
            exit();
        } else {
            // Si el registro falló (ej. email ya existe), muestra el mensaje de error.
            $error = $result['message'];
        }
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear cuenta</title>
    <link rel="icon" href="img/icon.png"></link>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <img src="img/background.png" class="background">

    <a href="index.php" class="back-home">
        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        Volver al inicio
    </a>

    <div class="auth-container <?php if ($error) echo 'has-error'; ?>">
        <div class="auth-card">
            <div class="auth-header">
                <h1>Crea tu cuenta</h1>
                <p>Libros al alcance de un click</p>
            </div>
            <?php if ($error): ?>
                <div class="error-message">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">

                <div class="form-group">
                    <label for="name">¿Cuál es tu nombre?</label>
                    <input type="text" id="name" name="name" class="form-control" required>
                </div>                

                <div class="form-group">
                    <label for="email">Correo institucional</label>
                    <input type="email" id="email" name="email" class="form-control"
                        value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
                </div>

                <div class="form-group">
                    <label for="password">Crea una contraseña</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="cpassword">Confirma tu contraseña</label>
                    <input type="password" id="cpassword" name="cpassword" class="form-control" required>
                </div>

                <div class="form-group full-width">
                    <label for="location">Localidad de residencia</label>
                    <select id="location" name="location" class="form-control" required>
                        <option value="" disabled selected>Selecciona tu localidad</option>
                        <option value="Usaquén">Usaquén</option>
                        <option value="Chapinero">Chapinero</option>
                        <option value="Santa Fe">Santa Fe</option>
                        <option value="San Cristóbal">San Cristóbal</option>
                        <option value="Usme">Usme</option>
                        <option value="Tunjuelito">Tunjuelito</option>
                        <option value="Bosa">Bosa</option>
                        <option value="Kennedy">Kennedy</option>
                        <option value="Fontibón">Fontibón</option>
                        <option value="Engativá">Engativá</option>
                        <option value="Suba">Suba</option>
                        <option value="Barrios Unidos">Barrios Unidos</option>
                        <option value="Teusaquillo">Teusaquillo</option>
                        <option value="Los Mártires">Los Mártires</option>
                        <option value="Antonio Nariño">Antonio Nariño</option>
                        <option value="Puente Aranda">Puente Aranda</option>
                        <option value="La Candelaria">La Candelaria</option>
                        <option value="Rafael Uribe Uribe">Rafael Uribe Uribe</option>
                        <option value="Ciudad Bolívar">Ciudad Bolívar</option>
                        <option value="Sumapaz">Sumapaz</option>
                    </select>
                </div>

                <button type="submit" class="auth-button full-width" href="index.php">Crear cuenta</button>
            </form>

            <div class="auth-links">
                <p>¿Ya tienes una cuenta? <a href="login.php">Inicia sesión aquí</a></p>
            </div>
        </div>
    </div>

    <style>
        body,
        html {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: 'HovesDemiBoldItalic';
            overflow: auto;
        }

        .background{
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
            margin-top: -1rem;
            min-height: 100%;
            height: 100vh;
            height: auto;
            display: flex;
            align-items: center;
            justify-content: center;
            padding-top: 1rem;
            position: relative;
            z-index: 1;
        }

        .auth-container.has-error {
            padding-top: 3rem;
            padding-bottom: 2rem;
        }        

        .auth-card {
            background: rgba(255, 255, 255, 1);
            border-radius: var(--radius, 1.5rem);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 1rem 4.2rem;
            width: 100%;
            max-width: 30vw;
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
            font-family: 'HovesDemiBoldItalic';
            color: #000000ff
        }

        form {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        }        
        
        .form-group {
            text-align: left;
            margin-bottom: 0;

        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        .form-group label {
            font-family: 'HovesDemiBoldItalic';
            font-size: 1rem;
            color: #000000ff;
            margin-bottom: 0.5rem;
            display: flex;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #b1b1b173;
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

        .auth-button.full-width {
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
            margin-bottom: 1.2rem;
            margin-top: 0.4rem;
            grid-column: 1 / -1;
        }

        .auth-button:hover {
            background-color: var(--color-accent-hover, #ffffffff);
            color: #000000ff;
            transform: translateY(-0.1px);
            box-shadow: 1px 2px 2px rgba(0, 0, 0, 0.22);
        }

        .auth-links p {
            font-family: 'HovesDemiBoldItalic';
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