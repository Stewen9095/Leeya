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
    if (!empty($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
        header('Location: adminpanel.php');
        exit();
    }
} else {
    header('Location: index.php');
    exit();
}

$action_message = '';
$action_error = '';

// Procesar acciones solo en POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $is_logged_in) {
    if (isset($_POST['cancel_proposal'])) {
        $proposal_id = intval($_POST['cancel_proposal']);
        $result = updateProposalStatus($proposal_id, 'Cancelada');
        $_SESSION['action_message'] = $result ? 'Propuesta cancelada.' : 'Error al cancelar propuesta.';
        header("Location: myproposals.php");
        exit();
    }
    if (isset($_POST['accept_proposal'])) {
        $proposal_id = intval($_POST['accept_proposal']);
        $result = finalizeProposal($proposal_id);
        $_SESSION['action_message'] = $result ? 'Propuesta aceptada y libro marcado como no disponible.' : 'Error al aceptar propuesta.';
        header("Location: myproposals.php");
        exit();
    }
    if (isset($_POST['reject_proposal'])) {
        $proposal_id = intval($_POST['reject_proposal']);
        $result = updateProposalStatus($proposal_id, 'Rechazada');
        $_SESSION['action_message'] = $result ? 'Propuesta rechazada.' : 'Error al rechazar propuesta.';
        header("Location: myproposals.php");
        exit();
    }
}

// Mensajes POST/REDIRECT/GET
if (isset($_SESSION['action_message'])) {
    $action_message = $_SESSION['action_message'];
    unset($_SESSION['action_message']);
}

$sent_proposals = getSentProposals($_SESSION['user_id']);
$received_proposals = getReceivedProposals($_SESSION['user_id']);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis propuestas</title>
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
                    html {
                        font-size: 15px;
                    }

                    body {
                        margin: 0;
                        font-family: 'HovesDemiBoldItalic';
                        background: #000;
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

    <?php if ($action_message): ?>
        <div class="success-message" style="max-width:700px;margin:1rem auto;"><?= htmlspecialchars($action_message) ?>
        </div>
    <?php endif; ?>

    <div style="max-width:700px;margin:2rem auto;">
        <h2 style="color:#fff;">Propuestas que hice</h2>
        <?php if (empty($sent_proposals)): ?>
            <div class="error-message">No has realizado propuestas.</div>
        <?php else: ?>
            <?php foreach ($sent_proposals as $p): ?>
                <div
                    style="background:#001aafff;border-radius:1rem;padding:1rem;margin-bottom:1rem;display:flex;align-items:center;gap:1.5rem;">
                    <img src="<?= htmlspecialchars($p['bookpic']) ?>" alt="Libro"
                        style="width:70px;height:100px;border-radius:1rem;">
                    <div style="flex:1;">
                        <b><?= htmlspecialchars($p['book_name']) ?></b> (<?= htmlspecialchars($p['author']) ?>)<br>
                        <span style="color:#fff;">Tipo: <?= htmlspecialchars($p['typeof']) ?> | Precio:
                            <?= $p['price'] !== null ? '$' . htmlspecialchars($p['price']) : 'N/A' ?></span><br>
                        <span style="color:#fff;">Estado: <?= htmlspecialchars($p['status']) ?></span><br>
                        <span style="color:#fff;">Dueño: <?= htmlspecialchars($p['owner_name']) ?></span>
                    </div>
                    <div style="display:flex;flex-direction:column;gap:0.5rem;">
                        <a href="https://outlook.office.com/mail/deeplink/compose?to=<?= urlencode($user['email']) ?>&subject=Consulta&body=Hola,%20estoy%20interesado%20en%20el%20libro"
                            target="_blank">
                            Contactar
                        </a>
                        <?php if ($p['status'] === 'En proceso'): ?>
                            <form method="post" style="margin:0;">
                                <input type="hidden" name="cancel_proposal" value="<?= $p['id'] ?>">
                                <button type="submit" class="btn-cancel"
                                    onclick="return confirm('¿Cancelar esta propuesta?');">Cancelar</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if ($p['typeof'] === 'Venta' && $p['money'] !== null): ?>
                    <span style="color:#ffd700;">Monto ofrecido: $<?= htmlspecialchars($p['money']) ?></span><br>
                <?php endif; ?>

                <?php if ($p['typeof'] === 'Intercambio'): ?>
                    <?php
                    $exchange_books = getExchangeBooks($p['id']);
                    if ($exchange_books):
                        ?>
                        <span style="color:#ffd700;">Libros ofrecidos:</span>
                        <ul style="margin:0.3rem 0 0 0.5rem;padding:0;">
                            <?php foreach ($exchange_books as $eb): ?>
                                <li>
                                    <img src="<?= htmlspecialchars($eb['bookpic']) ?>" alt="Libro"
                                        style="width:30px;height:40px;border-radius:0.3rem;vertical-align:middle;">
                                    <b><?= htmlspecialchars($eb['name']) ?></b> (<?= htmlspecialchars($eb['author']) ?>)
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div style="max-width:700px;margin:2rem auto;">
        <h2 style="color:#fff;">Propuestas que recibí</h2>
        <?php if (empty($received_proposals)): ?>
            <div class="error-message">No has recibido propuestas.</div>
        <?php else: ?>
            <?php foreach ($received_proposals as $p): ?>
                <div
                    style="background:#000080;border-radius:1rem;padding:1rem;margin-bottom:1rem;display:flex;align-items:center;gap:1.5rem;">
                    <img src="<?= htmlspecialchars($p['bookpic']) ?>" alt="Libro"
                        style="width:70px;height:100px;border-radius:1rem;">
                    <div style="flex:1;">
                        <b><?= htmlspecialchars($p['book_name']) ?></b> (<?= htmlspecialchars($p['author']) ?>)<br>
                        <span style="color:#fff;">Tipo: <?= htmlspecialchars($p['typeof']) ?> | Precio:
                            <?= $p['price'] !== null ? '$' . htmlspecialchars($p['price']) : 'N/A' ?></span><br>
                        <span style="color:#fff;">Estado: <?= htmlspecialchars($p['status']) ?></span><br>
                        <span style="color:#fff;">Interesado: <?= htmlspecialchars($p['interested_name']) ?></span>
                    </div>
                    <div style="display:flex;flex-direction:column;gap:0.5rem;">
                        <a href="https://outlook.office.com/mail/deeplink/compose?to=<?= urlencode($user['email']) ?>&subject=Consulta&body=Hola,%20estoy%20interesado%20en%20el%20libro"
                            target="_blank">
                            Contactar
                        </a>
                        <?php if ($p['status'] === 'En proceso'): ?>
                            <form method="post" style="margin:0;">
                                <input type="hidden" name="accept_proposal" value="<?= $p['id'] ?>">
                                <button type="submit" class="btn-save"
                                    onclick="return confirm('¿Aceptar esta propuesta?');">Aceptar</button>
                            </form>
                            <form method="post" style="margin:0;">
                                <input type="hidden" name="reject_proposal" value="<?= $p['id'] ?>">
                                <button type="submit" class="btn-cancel"
                                    onclick="return confirm('¿Rechazar esta propuesta?');">Rechazar</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
                <?php if ($p['typeof'] === 'Venta' && $p['money'] !== null): ?>
                    <span style="color:#ffd700;">Monto ofrecido: $<?= htmlspecialchars($p['money']) ?></span><br>
                <?php endif; ?>

                <?php if ($p['typeof'] === 'Intercambio'): ?>
                    <?php
                    $exchange_books = getExchangeBooks($p['id']);
                    if ($exchange_books):
                        ?>
                        <span style="color:#ffd700;">Libros ofrecidos:</span>
                        <ul style="margin:0.3rem 0 0 0.5rem;padding:0;">
                            <?php foreach ($exchange_books as $eb): ?>
                                <li>
                                    <img src="<?= htmlspecialchars($eb['bookpic']) ?>" alt="Libro"
                                        style="width:30px;height:40px;border-radius:0.3rem;vertical-align:middle;">
                                    <b><?= htmlspecialchars($eb['name']) ?></b> (<?= htmlspecialchars($eb['author']) ?>)
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

</body>

</html>