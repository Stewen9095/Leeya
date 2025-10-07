<?php
session_start();
require_once 'auth_functions.php';
require_once 'database.php';

$is_logged_in = isset($_SESSION['user_id']);
$current_user_id = $_SESSION['user_id'] ?? null;

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

// Procesar propuesta SOLO en POST, luego redirigir
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
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Detalle del libro</title>
    <link rel="stylesheet" href="style.css">

    <style>
        .proposal-message {
            background: #f0fff4;
            color: #38a169;
            padding: 0.75rem;
            border-radius: 1rem;
            margin-bottom: 1rem;
            font-size: 1rem;
            border: 1px solid #c6f6d5;
        }

        .proposal-error {
            background: #fee;
            color: #c53030;
            padding: 0.75rem;
            border-radius: 1rem;
            margin-bottom: 1rem;
            font-size: 1rem;
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

<body style="background:#000; color:#fff; font-family:'Inter',sans-serif;">
    <div
        style="max-width:600px;margin:2rem auto;background:#fff;border-radius:2rem;padding:2rem;box-shadow:0 0 1rem #0002;">
        <div style="display:flex;gap:2rem;">
            <div style="width:160px;height:220px;overflow:hidden;border-radius:1rem;background:#eee;">
                <img src="<?= htmlspecialchars($book['bookpic']) ?>" alt="Imagen del libro"
                    style="width:100%;height:100%;object-fit:cover;">
            </div>
            <div style="flex:1;">
                <h2 style="color:#001aaf;margin-top:0;"><?= htmlspecialchars($book['name']) ?></h2>
                <p><b>Autor:</b> <?= htmlspecialchars($book['author']) ?></p>
                <p><b>Editorial:</b> <?= htmlspecialchars($book['editorial']) ?></p>
                <p><b>Género:</b> <?= htmlspecialchars($book['genre']) ?></p>
                <p><b>Descripción:</b> <?= htmlspecialchars($book['description']) ?></p>
                <p><b>Estado:</b>
                    <?php
                    $stars = '';
                    for ($i = 0; $i < 5; $i++) {
                        $stars .= $i < intval($book['qstatus']) ? '⭐' : '☆';
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
                <a href="chat.php?bookid=<?= $book['id'] ?>" class="functions">Chat con dueño</a>
            </div>
        <?php elseif (!$is_logged_in): ?>
            <div style="margin-top:1.5rem;">
                <a href="login.php" class="functions">Inicia sesión para ofertar o chatear</a>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>