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
        $user_role = htmlspecialchars($_SESSION['user_role'] ?? 'user');
    }

}

if (isset($_SESSION['user_id'])) {
    if (!empty($_SESSION['user_role']) && $_SESSION['user_role'] === 'banned') {
        header('Location: banned.php');
        exit();
    }
}

// Require login to view user profiles
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}


$current_user_id = $_SESSION['user_id'] ?? null;

$user_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($user_id <= 0) {
    header('Location: index.php');
    exit();
}

$user = getUserById($user_id);
if (!$user) {
    header('Location: index.php');
    exit();
}

// Si el usuario está banneado y no es admin, redirigir
if ($user['userrole'] === 'banned' && $user_role !== 'admin') {
    header('Location: index.php');
    exit();
}

$rates = getUserRates($user_id);

$rate_message = '';
$rate_error = '';
$report_message = '';
$report_error = '';
$ban_message = '';

// Procesar rate, report y ban solo en POST, luego redirigir
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $is_logged_in) {
    if (isset($_POST['rate_user'])) {
        $stars = intval($_POST['stars'] ?? 0);
        $description = trim($_POST['rate_description'] ?? '');
        if ($stars < 1 || $stars > 5 || $description === '') {
            $_SESSION['rate_error'] = 'Debes ingresar estrellas (1-5) y una descripción.';
        } else {
            $result = createUserRate($current_user_id, $user_id, $stars, $description);
            $_SESSION['rate_message'] = $result ? '¡Reseña enviada!' : 'Error al enviar la reseña.';
        }
        header("Location: pickeduser.php?id=" . $user_id);
        exit();
    }
    if (isset($_POST['report_user'])) {
        $motive = $_POST['motive'] ?? '';
        $description = trim($_POST['report_description'] ?? '');
        if ($motive === '' || $description === '') {
            $_SESSION['report_error'] = 'Debes seleccionar un motivo y escribir una descripción.';
        } else {
            $result = createUserReport($current_user_id, $user_id, $motive, $description);
            $_SESSION['report_message'] = $result ? '¡Reporte enviado!' : 'Error al enviar el reporte.';
        }
        header("Location: pickeduser.php?id=" . $user_id);
        exit();
    }
    if (isset($_POST['ban_user'])) {
        if ($user_role === 'admin' && $current_user_id != $user_id) {
            $result = banUser($user_id);
            $_SESSION['ban_message'] = $result['message'];
        }
        header("Location: pickeduser.php?id=" . $user_id);
        exit();
    }
    if (isset($_POST['unban_user'])) {
        if ($user_role === 'admin' && $current_user_id != $user_id) {
            $result = unbanUser($user_id);
            $_SESSION['ban_message'] = $result['message'];
        }
        header("Location: pickeduser.php?id=" . $user_id);
        exit();
    }
}

// Mensajes POST/REDIRECT/GET
if (isset($_SESSION['rate_message'])) {
    $rate_message = $_SESSION['rate_message'];
    unset($_SESSION['rate_message']);
}
if (isset($_SESSION['rate_error'])) {
    $rate_error = $_SESSION['rate_error'];
    unset($_SESSION['rate_error']);
}
if (isset($_SESSION['report_message'])) {
    $report_message = $_SESSION['report_message'];
    unset($_SESSION['report_message']);
}
if (isset($_SESSION['report_error'])) {
    $report_error = $_SESSION['report_error'];
    unset($_SESSION['report_error']);
}
if (isset($_SESSION['ban_message'])) {
    $ban_message = $_SESSION['ban_message'];
    unset($_SESSION['ban_message']);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuario</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="img/icon.png" type="image/png">
</head>

<body>
    <header>

        <?php
        $pending_counts = getPendingProposalsCount($_SESSION['user_id']);
        $total_pending = $pending_counts['sent'] + $pending_counts['received'];
        $badge_text = $total_pending > 9 ? '+9' : ($total_pending > 0 ? $total_pending : '');
        ?>

        <nav>
            <a href="index.php">
                <img src="img/icono.png" class="iconoimg" alt="Leeya icono">
            </a>
            <div class="nav-btns">

                <a href="explore.php">
                    <h3>EXPLORAR</h3>
                </a>

                <?php if ($is_logged_in): ?>
                    <a href="newbook.php">
                        <h3>+</h3>
                    </a>


                <?php elseif (!$is_logged_in): ?>

                    <a href="login.php">
                        <h3>INICIAR SESIÓN</h3>
                    </a>

                <?php endif; ?>

                <?php if ($is_logged_in): ?>

                    <a class="circle" href="mymessages.php">
                        <img src="img/mensajeria.png" alt="Mensajeria" class="noti-icon">
                    </a>

                    <a class="circle" href="myproposals.php" style="position:relative;">
                        <img src="img/noti.png" alt="Notificación" class="noti-icon">
                        <?php if ($badge_text): ?>
                            <span style="
                                position:absolute;
                                top:-0.3rem; right:-0.3rem;
                                background:#ff2d55;
                                color:#fff;
                                font-size:0.85rem;
                                font-family:'HovesExpandedBold';
                                border-radius:1rem;
                                padding:0.15rem 0.5rem;
                                min-width:1.5rem;
                                text-align:center;
                                box-shadow:0 0 0.2rem #0005;
                                z-index:2;
                            "><?= $badge_text ?></span>
                        <?php endif; ?>
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

        .profile-container {
            max-width: 80rem;
            margin: 1.5rem auto;
            height: 16rem;
            margin-top: -1.2rem;
            background: linear-gradient(to bottom,
                    #001aafff 0%,
                    #000080 55%);
            border-radius: 2rem;
            box-shadow: 0 0 0.5rem rgba(240, 240, 240, 0.05);
            padding: 1rem 1rem 1rem 1rem;
            display: flex;
            flex-direction: row;
            align-items: center;
            gap: 1rem;
        }

        header {
            background: transparent;
            box-shadow: none;
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
    </style>

    <div class="profile-container">
        <div class="dataUser">
            <h1 class="welcome"><?= htmlspecialchars($user['name']) ?></h1>
            <p class="since">Usuario activo desde: <?= htmlspecialchars(explode(' ', $user['signdate'])[0]) ?></p>
            <p class="infotext">Correo electrónico de contacto: <?= htmlspecialchars($user['email']) ?></p>
            <p class="infotext">Ubicación: <?= htmlspecialchars($user['location']) ?></p>
            <?php if ($user['lildescription'] == ''): ?>
                <p class="infotextfinal">Aún no cuenta con una descripción</p>
            <?php else: ?>
                <p class="infotextfinal">Descripción: <?= htmlspecialchars($user['lildescription']) ?></p>
            <?php endif; ?>

            <a href="https://outlook.office.com/mail/deeplink/compose?to=<?= urlencode($user['email']) ?>&subject=Consulta&body=Hola,%20estoy%20interesado%20en%20el%20libro"
                target="_blank">
                Contactar
            </a>
        </div>
    </div>

    <div style="max-width:900px;margin:2rem auto;">
        <h2 style="color:#fff;">Reseñas del usuario</h2>
        <?php if ($rate_message): ?>
            <div class="success-message"><?= htmlspecialchars($rate_message) ?></div>
        <?php endif; ?>
        <?php if ($rate_error): ?>
            <div class="error-message"><?= htmlspecialchars($rate_error) ?></div>
        <?php endif; ?>
        <?php if (empty($rates)): ?>
            <div class="error-message">Este usuario aún no tiene reseñas.</div>
        <?php else: ?>
            <?php foreach ($rates as $r): ?>
                <div style="background:#001aafff;border-radius:1rem;padding:1rem;margin-bottom:1rem;">
                    <b><?= htmlspecialchars($r['sender_name']) ?></b>
                    <span style="color:#ffd700;">
                        <?php for ($i = 0; $i < intval($r['rating']); $i++)
                            echo ' ★ '; ?>
                        <?php for ($i = intval($r['rating']); $i < 5; $i++)
                            echo ' ☆ '; ?>
                    </span>
                    <span style="color:#fff;"> | <?= htmlspecialchars($r['ratedate']) ?></span>
                    <p style="margin:0.5rem 0 0 0;color:#fff;"><?= htmlspecialchars($r['commentary']) ?></p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <?php if ($is_logged_in && $current_user_id != $user_id && $user_role !== 'admin'): ?>
            <form method="post" style="background:#000080;padding:1rem;border-radius:1rem;margin-top:1.5rem;">
                <h3 style="color:#fff;">Escribir una reseña</h3>
                <label for="stars" style="color:#fff;">Estrellas:</label>
                <select name="stars" id="stars" required>
                    <option value="">Selecciona</option>
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <option value="<?= $i ?>"><?= $i ?></option>
                    <?php endfor; ?>
                </select>
                <br>
                <label for="rate_description" style="color:#fff;">Descripción:</label>
                <textarea name="rate_description" id="rate_description" rows="2" required style="width:100%;"></textarea>
                <br>
                <button type="submit" name="rate_user" class="btn-save" style="margin-top:0.5rem;">Enviar reseña</button>
            </form>
        <?php endif; ?>
    </div>

    <div style="max-width:900px;margin:2rem auto;">
        <?php if ($user_role !== 'admin'): ?>
            <h2 style="color:#fff;">Reportar usuario</h2>
            <?php if ($report_message): ?>
                <div class="success-message"><?= htmlspecialchars($report_message) ?></div>
            <?php endif; ?>
            <?php if ($report_error): ?>
                <div class="error-message"><?= htmlspecialchars($report_error) ?></div>
            <?php endif; ?>
            <?php if ($is_logged_in && $current_user_id != $user_id): ?>
                <form method="post" style="background:#000080;padding:1rem;border-radius:1rem;">
                    <label for="motive" style="color:#fff;">Motivo:</label>
                    <select name="motive" id="motive" required>
                        <option value="">Selecciona</option>
                        <option>Estafa o fraude</option>
                        <option>Producto falso o réplica</option>
                        <option>Suplantación de identidad</option>
                        <option>Información o fotos falsas</option>
                        <option>Lenguaje ofensivo o acoso</option>
                        <option>Venta de productos prohibidos</option>
                        <option>Contenido inapropiado o ilegal</option>
                        <option>Solicitud de datos personales</option>
                        <option>Phishing o enlaces maliciosos</option>
                        <option>Incumplimiento en la entrega</option>
                    </select>
                    <br>
                    <label for="report_description" style="color:#fff;">Descripción:</label>
                    <textarea name="report_description" id="report_description" rows="2" required
                        style="width:100%;"></textarea>
                    <br>
                    <button type="submit" name="report_user" class="btn-cancel" style="margin-top:0.5rem;">Enviar
                        reporte</button>
                </form>
            <?php endif; ?>
        <?php else: ?>
            <h2 style="color:#fff;">Gestión administrativa</h2>
            <?php if ($ban_message): ?>
                <div class="success-message"><?= htmlspecialchars($ban_message) ?></div>
            <?php endif; ?>
            <?php if ($current_user_id != $user_id): ?>
                <form method="post" style="background:#000080;padding:1rem;border-radius:1rem;">
                    <p style="color:#fff;">Como administrador, puedes gestionar este usuario.</p>
                    <?php if ($user['userrole'] === 'banned'): ?>
                        <button type="submit" name="unban_user" class="btn-cancel" style="margin-top:0.5rem;background:#28a745;">Desbannear usuario</button>
                    <?php else: ?>
                        <button type="submit" name="ban_user" class="btn-cancel" style="margin-top:0.5rem;background:#d9534f;">Bannear usuario</button>
                    <?php endif; ?>
                </form>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</body>

</html>