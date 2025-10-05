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

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle del libro</title>
    <link rel="stylesheet" href="style.css">
</head>
<body style="background:#000; color:#fff; font-family:'Inter',sans-serif;">
    <div style="max-width:600px;margin:2rem auto;background:#fff;border-radius:2rem;padding:2rem;box-shadow:0 0 1rem #0002;">
        <div style="display:flex;gap:2rem;">
            <div style="width:160px;height:220px;overflow:hidden;border-radius:1rem;background:#eee;">
                <img src="<?= htmlspecialchars($book['bookpic']) ?>" alt="Imagen del libro" style="width:100%;height:100%;object-fit:cover;">
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
                <?php if ($book['price'] !== null): ?>
                    <p><b>Precio:</b> $<?= htmlspecialchars($book['price']) ?></p>
                <?php endif; ?>
            </div>
        </div>
        <hr>
        <?php if (!$is_owner && $is_logged_in): ?>
            <div style="margin-top:1.5rem;display:flex;gap:1rem;">
                <a href="startProposal.php?bookid=<?= $book['id'] ?>" class="functions">Iniciar oferta</a>
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