<?php
session_start();
require_once 'auth_functions.php';
require_once 'database.php';

$is_logged_in = isset($_SESSION['user_id']);
$current_user_id = $_SESSION['user_id'] ?? null;
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

// Validar parámetro id
$book_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($book_id <= 0) {
    header('Location: index.php');
    exit();
}

$book = getBookById($book_id);

if (!$book) {
    header('Location: index.php');
    exit();
}

// Si el libro no está disponible, redirige
if (!$book['status']) {
    header('Location: index.php');
    exit();
}

// Si el usuario es el dueño, no mostrar botones de oferta/chat
$is_owner = ($is_logged_in && $current_user_id == $book['ownerid']);

// Mensajes POST/REDIRECT/GET
$proposal_message = '';
$proposal_error = '';
if (isset($_SESSION['proposal_message'])) {
    $proposal_message = $_SESSION['proposal_message'];
    unset($_SESSION['proposal_message']);
}
if (isset($_SESSION['proposal_error'])) {
    $proposal_error = $_SESSION['proposal_error'];
    unset($_SESSION['proposal_error']);
}

// Procesar propuesta POST, luego redirigir
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$is_owner && $is_logged_in) {
    if ($book['typeof'] === 'Donacion') {
        $proposal_id = createProposal($current_user_id, $book['id'], 'Donacion');
        $_SESSION['proposal_message'] = $proposal_id ? '¡Propuesta de donación registrada!' : 'Error al registrar la propuesta.';
    } elseif ($book['typeof'] === 'Venta') {
        $amount = floatval($_POST['amount'] ?? 0);
        if ($amount > 0) {
            $proposal_id = createProposal($current_user_id, $book['id'], 'Venta', $amount);
            $_SESSION['proposal_message'] = $proposal_id ? '¡Propuesta de compra registrada!' : 'Error al registrar la propuesta.';
        } else {
            $_SESSION['proposal_error'] = 'Debes ingresar un monto válido.';
        }
    } elseif ($book['typeof'] === 'Intercambio') {
        $offered_books = $_POST['offered_books'] ?? [];
        if (!empty($offered_books)) {
            $proposal_id = createExchangeProposal($current_user_id, $book['id'], $offered_books);
            $_SESSION['proposal_message'] = $proposal_id ? '¡Propuesta de intercambio registrada!' : 'Error al registrar la propuesta.';
        } else {
            $_SESSION['proposal_error'] = 'Debes seleccionar al menos un libro para intercambiar.';
        }
    } elseif ($book['typeof'] === 'Subasta') {
        $amount = floatval($_POST['amount'] ?? 0);
        $base = floatval($book['price']);
        if ($amount > $base) {
            $proposal_id = createAuctionProposal($current_user_id, $book['id'], $amount);
            $_SESSION['proposal_message'] = $proposal_id ? '¡Puja registrada y monto actualizado!' : 'Error al registrar la puja.';
        } else {
            $_SESSION['proposal_error'] = 'La puja debe ser mayor al monto base actual.';
        }
    }
    // Redirige para evitar duplicados al recargar
    header("Location: pickedbook.php?id=" . $book['id']);
    exit();
}

// Para intercambio: obtener libros del usuario
$user_books = [];
if ($book['typeof'] === 'Intercambio' && $is_logged_in) {
    $user_books = getBooksByUserId($current_user_id);
}

$edit_mode = isset($_GET['edit']) && $is_owner;
$edit_message = '';
$edit_error = '';

// Procesar edición
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $is_owner && isset($_POST['edit_book'])) {
    $update_data = [
        'name' => $_POST['name'] ?? $book['name'],
        'author' => $_POST['author'] ?? $book['author'],
        'editorial' => $_POST['editorial'] ?? $book['editorial'],
        'genre' => $_POST['genre'] ?? $book['genre'],
        'description' => $_POST['description'] ?? $book['description'],
        'qstatus' => $_POST['qstatus'] ?? $book['qstatus'],
        'bookpic' => $_POST['bookpic'] ?? $book['bookpic'],
        'typeof' => $_POST['typeof'] ?? $book['typeof'],
    ];

    // Según tipo, agrega campos
    if ($_POST['typeof'] === 'Venta' || $_POST['typeof'] === 'Subasta') {
        $update_data['price'] = $_POST['price'] ?? $book['price'];
    } else {
        $update_data['price'] = null;
    }
    if ($_POST['typeof'] === 'Subasta') {
        $update_data['limdate'] = $_POST['limdate'] ?? $book['limdate'];
    } else {
        $update_data['limdate'] = null;
    }

    $result = updateBook($book['id'], $update_data);
    if ($result) {
        $_SESSION['edit_message'] = "¡Libro actualizado correctamente!";
    } else {
        $_SESSION['edit_error'] = "Error al actualizar el libro.";
    }
    // Redirige para evitar duplicados al recargar
    header("Location: pickedbook.php?id=" . $book['id'] . "&edit=1");
    exit();
}

// Mensajes POST/REDIRECT/GET para edición
$edit_message = '';
$edit_error = '';
if (isset($_SESSION['edit_message'])) {
    $edit_message = $_SESSION['edit_message'];
    unset($_SESSION['edit_message']);
}
if (isset($_SESSION['edit_error'])) {
    $edit_error = $_SESSION['edit_error'];
    unset($_SESSION['edit_error']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $is_owner && isset($_POST['delete_book'])) {
    $deleted = deleteBook($book['id']);
    if ($deleted) {
        header("Location: user.php");
        exit();
    } else {
        $edit_error = "Error al eliminar el libro.";
    }
}

?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Detalle del libro</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="img/icon.png" type="image/png">

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

        header {
            background: transparent;
            box-shadow: none;
        }

        .form-whole {
            max-width: 88%;
            margin: 1.5rem auto;
            height: 27.8rem;
            max-height: 27.8rem;
            margin-top: -1.2rem;
            background: linear-gradient(to bottom, #000080 0%, #001aafff 70%);
            border-radius: 2rem;
            box-shadow: 0 0 0.5rem rgba(240, 240, 240, 0.05);
            padding: 1.5rem 1.8rem 1.5rem 1.8rem;
            display: flex;
            flex-direction: row;
            align-items: center;
            gap: 1.8rem;
        }

        .bookpic {
            text-align: center;
            width: 30%;
            height: 62%;
            border-radius: 2rem;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            position: relative;
        }

        .realpic {
            width: 100%;
            height: 88%;
            margin-top: 0%;
            background: white;
            align-items: center;
            overflow: hidden;
            border-radius: 1.5rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.5);
        }

        .realpic img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            border-radius: 1.5rem;
            max-width: 100%;
            max-height: 100%;
            image-rendering: auto;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.5);
        }

        .bookinfo {
            text-align: center;
            width: 61%;
            height: 96%;
            border-radius: 2rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1rem 0;
        }

        .bookinfo h2 {
            font-family: "HovesExpandedBold";
            font-size: clamp(1rem, 2vw, 1.5rem);
            color: white;
            text-transform: uppercase;
            letter-spacing: 0.05rem;
            margin-top: 0rem;
            margin-bottom: 0rem;
        }

        .book-form {
            width: 85%;
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 0.2rem 1rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            text-align: left;
            border: none;
            box-shadow: none;
            background: none;
        }

        .form-group label {
            font-weight: 600;
            font-family: "HovesExpandedBold";
            color: white;
            font-size: clamp(0.75rem, 1.5vw, 0.95rem);
            margin-bottom: 0.15rem;
        }

        .form-group input,
        .form-group textarea {
            padding: 0.4rem 0.8rem;
            font-size: clamp(0.8rem, 1.6vw, 1rem);
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 1vw;
            background-color: #fff;
            color: #000080;
            box-shadow: none !important;
            outline: none;
            appearance: none;
            transition: border-color 0.15s ease;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            box-shadow: none;
            outline: none;
        }

        .form-group select {
            padding: 0.4rem 0.4rem;
            border-radius: 1rem;
            border: 1.8px solid #001aaf;
            font-size: clamp(0.8rem, 1.6vw, 1rem);
            outline: none;
            transition: all 0.25s ease;
            background-color: #fff;
            color: #000;
            cursor: pointer;
        }

        .form-buttons {
            grid-column: span 2;
            display: flex;
            justify-content: center;
            gap: 1.6rem;
            margin-top: 1.2rem;
        }

        .form-buttons button {
            padding: 0.6rem 1.4rem;
            font-size: clamp(0.8rem, 1.5vw, 1rem);
            cursor: pointer;
            border: none;
            color: white;
            background-color: #000080;
            border-radius: var(--radius, 8px);
            font-family: "HovesExpandedBold";
            transition: background-color 0.5s, transform 0.5s;
        }

        .btn-save:hover,
        .btn-cancel:hover {
            background: #fff;
            color: #000080;
        }

        .success-message {
            background: #f0fff4;
            color: #38a169;
            padding: 0.75rem;
            border-radius: 1rem;
            margin-bottom: 1rem;
            font-size: 0.9rem;
            border: 1px solid #c6f6d5;
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

        .exchange-list {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .exchange-item {
            background: #f5f5f5;
            border-radius: 1rem;
            padding: 0.5rem 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 0.125rem 0.5rem #0001;
        }

        .exchange-item label {
            cursor: pointer;
            font-size: 1rem;
        }

        .exchange-item input[type="checkbox"] {
            accent-color: #001aaf;
            width: 1.2rem;
            height: 1.2rem;
        }
    </style>

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

    <div
        style="max-width:600px;margin:2rem auto;background:#fff;border-radius:2rem;padding:2rem;box-shadow:0 0 1rem #0002;">
        <div style="display:flex;gap:2rem;">
            <div style="width:160px;height:220px;overflow:hidden;border-radius:1rem;background:#eee;">
                <img src="<?= htmlspecialchars($book['bookpic']) ?>" alt="Imagen del libro"
                    style="width:100%;height:100%;object-fit:cover;">
            </div>
            <div style="flex:1;">
                <?php if ($is_owner && !$edit_mode): ?>
                    <form method="get" style="display:inline;">
                        <input type="hidden" name="id" value="<?= $book['id'] ?>">
                        <button type="submit" name="edit" value="1" class="functions"
                            style="margin-right:0.5rem;">Editar</button>
                    </form>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="delete_book" value="1">
                        <button type="submit" class="functions btn-cancel"
                            onclick="return confirm('¿Seguro que deseas eliminar este libro?');">Eliminar</button>
                    </form>
                <?php endif; ?>

                <?php if ($is_owner && $edit_mode): ?>
                    <?php if ($edit_message): ?>
                        <div class="proposal-message"><?= htmlspecialchars($edit_message) ?></div>
                    <?php endif; ?>
                    <?php if ($edit_error): ?>
                        <div class="proposal-error"><?= htmlspecialchars($edit_error) ?></div>
                    <?php endif; ?>
                    <form method="post">
                        <input type="hidden" name="edit_book" value="1">
                        <label>Título:</label>
                        <input type="text" name="name" value="<?= htmlspecialchars($book['name']) ?>" required>
                        <label>Autor:</label>
                        <input type="text" name="author" value="<?= htmlspecialchars($book['author']) ?>" required>
                        <label>Editorial:</label>
                        <input type="text" name="editorial" value="<?= htmlspecialchars($book['editorial']) ?>" required>
                        <label>Género:</label>
                        <input type="text" name="genre" value="<?= htmlspecialchars($book['genre']) ?>" required>
                        <label>Descripción:</label>
                        <textarea name="description" required><?= htmlspecialchars($book['description']) ?></textarea>
                        <label>Estado (0-5):</label>
                        <input type="number" name="qstatus" min="0" max="5"
                            value="<?= htmlspecialchars($book['qstatus']) ?>" required>
                        <label>Imagen (URL):</label>
                        <input type="text" name="bookpic" value="<?= htmlspecialchars($book['bookpic']) ?>" required>
                        <label>Tipo de operación:</label>
                        <select name="typeof" required>
                            <option value="Donacion" <?= $book['typeof'] == 'Donacion' ? 'selected' : ''; ?>>Donación</option>
                            <option value="Venta" <?= $book['typeof'] == 'Venta' ? 'selected' : ''; ?>>Venta</option>
                            <option value="Intercambio" <?= $book['typeof'] == 'Intercambio' ? 'selected' : ''; ?>>Intercambio
                            </option>
                            <option value="Subasta" <?= $book['typeof'] == 'Subasta' ? 'selected' : ''; ?>>Subasta</option>
                        </select>
                        <?php if ($book['typeof'] === 'Venta' || $book['typeof'] === 'Subasta'): ?>
                            <label>Precio:</label>
                            <input type="number" name="price" min="0" step="any"
                                value="<?= htmlspecialchars($book['price']) ?>">
                        <?php endif; ?>
                        <?php if ($book['typeof'] === 'Subasta'): ?>
                            <label>Fecha límite de subasta:</label>
                            <input type="date" name="limdate" value="<?= htmlspecialchars($book['limdate']) ?>">
                        <?php endif; ?>
                        <button type="submit" class="functions">Guardar cambios</button>
                    </form>
                <?php else: ?>
                    <h2 style="color:#001aaf;margin-top:0;"><?= htmlspecialchars($book['name']) ?></h2>
                    <p><b>Autor:</b> <?= htmlspecialchars($book['author']) ?></p>
                    <p><b>Editorial:</b> <?= htmlspecialchars($book['editorial']) ?></p>
                    <p><b>Género:</b> <?= htmlspecialchars($book['genre']) ?></p>
                    <p><b>Descripción:</b> <?= htmlspecialchars($book['description']) ?></p>
                    <p><b>Estado:</b>
                        <?php
                        $stars = '';
                        for ($i = 0; $i < 5; $i++) {
                            $stars .= $i < intval($book['qstatus']) ? '⭐' : ' ☆ ';
                        }
                        echo $stars;
                        ?>
                    </p>
                    <p><b>Tipo:</b> <?= htmlspecialchars($book['typeof']) ?></p>
                    <?php if ($book['typeof'] === 'Subasta' && !empty($book['limdate'])): ?>
                        <p><b>Fecha límite de subasta:</b> <?= htmlspecialchars($book['limdate']) ?></p>
                    <?php endif; ?>
                    <?php if ($book['price'] !== null): ?>
                        <p><b>Precio:</b> $<?= htmlspecialchars($book['price']) ?></p>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
        <hr>
        <?php if (!$is_owner && $is_logged_in): ?>
            <?php if ($proposal_message): ?>
                <div class="proposal-message"><?= htmlspecialchars($proposal_message) ?></div>
            <?php endif; ?>
            <?php if ($proposal_error): ?>
                <div class="proposal-error"><?= htmlspecialchars($proposal_error) ?></div>
            <?php endif; ?>

            <?php if ($book['typeof'] === 'Donacion'): ?>
                <form method="post">
                    <button type="submit" class="functions">Solicitar donación</button>
                </form>
            <?php elseif ($book['typeof'] === 'Venta'): ?>
                <form method="post" style="display:flex;gap:1rem;align-items:center;">
                    <input type="number" name="amount" min="1" step="any" placeholder="Monto a ofrecer" required>
                    <button type="submit" class="functions">Ofertar</button>
                </form>
            <?php elseif ($book['typeof'] === 'Intercambio'): ?>
                <?php if (empty($user_books)): ?>
                    <div class="proposal-error">No dispones de libros publicados para hacer un intercambio.</div>
                <?php else: ?>
                    <form method="post">
                        <label>Selecciona tus libros para intercambiar:</label>
                        <div class="exchange-list">
                            <?php foreach ($user_books as $ubook): ?>
                                <div class="exchange-item">
                                    <input type="checkbox" id="book<?= $ubook['id'] ?>" name="offered_books[]"
                                        value="<?= $ubook['id'] ?>">
                                    <label for="book<?= $ubook['id'] ?>">
                                        <b><?= htmlspecialchars($ubook['name']) ?></b> (<?= htmlspecialchars($ubook['author']) ?>)
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <button type="submit" class="functions">Proponer intercambio</button>
                    </form>
                <?php endif; ?>
            <?php elseif ($book['typeof'] === 'Subasta'): ?>
                <form method="post" style="display:flex;gap:1rem;align-items:center;">
                    <input type="number" name="amount" min="<?= floatval($book['price']) + 1 ?>" step="any"
                        placeholder="Monto a pujar" required>
                    <button type="submit" class="functions">Pujar</button>
                </form>
            <?php endif; ?>
            <div style="margin-top:1.5rem;display:flex;gap:1rem;">
            <a href="https://outlook.office.com/mail/deeplink/compose?to=<?= urlencode($user['email']) ?>&subject=Consulta&body=Hola,%20estoy%20interesado%20en%20el%20libro"
                target="_blank">
                Contactar
            </a>
            </div>
        <?php elseif (!$is_logged_in): ?>
            <div style="margin-top:1.5rem;">
                <a href="login.php" class="functions">Inicia sesión para ofertar o chatear</a>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>