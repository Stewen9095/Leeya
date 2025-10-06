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
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Explorar libros</title>
</head>

<body>

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
    </style>

    <nav>
        <a href="index.php">
            <img src="img/icono.png" class="iconoimg" alt="Leeya icono">
        </a>
        <div class="nav-btns">

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

                <a class="circle" href="myproposals.php">
                    <img src="img/noti.png" alt="Notificación" class="noti-icon">
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
                    font-family: 'HovesExpandedBold';
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

        </div>
    </nav>

    <?php
    // ...existing session and nav code...
    
    $search = trim($_GET['search'] ?? '');
    $type = trim($_GET['type'] ?? '');
    $search_user = trim($_GET['search_user'] ?? '');
    $exclude_user_id = $is_logged_in ? $_SESSION['user_id'] : null;

    // Libros filtrados
    $books = searchBooks($search, $type, $exclude_user_id);

    // Usuarios filtrados
    $users = [];
    if ($search_user !== '') {
        $users = searchUsers($search_user, $exclude_user_id);
    }
    ?>

    <!-- Barra de filtros -->
    <div style="max-width:1100px;margin:2vw auto 0 auto;">
        <form method="get"
            style="width:100%;margin-bottom:2vw;display:flex;gap:1vw;flex-wrap:wrap;justify-content:center;">
            <input type="text" name="search" value="<?= htmlspecialchars($search) ?>"
                placeholder="Buscar libro por título, autor o género..."
                style="flex:2;padding:0.8vw 1vw;border-radius:1vw;border:none;font-size:1.1vw;">
            <select name="type" style="flex:1;padding:0.8vw 1vw;border-radius:1vw;border:none;font-size:1.1vw;">
                <option value="">Todos</option>
                <option value="Donacion" <?= $type == 'Donacion' ? 'selected' : ''; ?>>Donación</option>
                <option value="Venta" <?= $type == 'Venta' ? 'selected' : ''; ?>>Venta</option>
                <option value="Intercambio" <?= $type == 'Intercambio' ? 'selected' : ''; ?>>Intercambio</option>
                <option value="Subasta" <?= $type == 'Subasta' ? 'selected' : ''; ?>>Subasta</option>
            </select>
            <button type="submit"
                style="background:#001aaf;color:#fff;border:none;padding:0.8vw 2vw;border-radius:1vw;font-size:1.1vw;cursor:pointer;">Buscar
                libro</button>
        </form>
        <form method="get"
            style="width:100%;margin-bottom:2vw;display:flex;gap:1vw;flex-wrap:wrap;justify-content:center;">
            <input type="text" name="search_user" value="<?= htmlspecialchars($search_user) ?>"
                placeholder="Buscar usuario por nombre..."
                style="flex:2;padding:0.8vw 1vw;border-radius:1vw;border:none;font-size:1.1vw;">
            <button type="submit"
                style="background:#001aaf;color:#fff;border:none;padding:0.8vw 2vw;border-radius:1vw;font-size:1.1vw;cursor:pointer;">Buscar
                usuario</button>
        </form>
    </div>

    <!-- Resultados de usuarios -->
    <?php if (!empty($users)): ?>
        <h2 style="color:#fff;text-align:center;width:100%;">Usuarios encontrados</h2>
        <div class="bookbox-container">
            <?php foreach ($users as $user): ?>
                <div class="fullbook">
                    <div class="bookbox" style="background:#eaeaea;display:flex;align-items:center;justify-content:center;">
                        <img src="img/user.png" alt="Usuario" style="width:60px;height:60px;border-radius:50%;background:#fff;">
                    </div>
                    <div class="infolibro">
                        <h3 class="TituloLibro"><?= htmlspecialchars($user['name']) ?></h3>
                        <p style="font-size:0.95vw;color:#555;margin:0 0 0.5vw 0;"><b>Email:</b>
                            <?= htmlspecialchars($user['email']) ?></p>
                        <p style="font-size:0.95vw;color:#555;margin:0 0 0.5vw 0;"><b>Ubicación:</b>
                            <?= htmlspecialchars($user['location']) ?></p>
                        <p style="font-size:0.95vw;color:#555;margin:0 0 0.5vw 0;"><b>Descripción:</b>
                            <?= htmlspecialchars($user['lildescription']) ?></p>
                        <div class="AdquirirLibro">
                            <a href="userprofile.php?id=<?= $user['id'] ?>">Ver perfil</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Resultados de libros -->
    <h2 style="color:#fff;text-align:center;width:100%;">Libros disponibles</h2>
    <div class="bookbox-container">
        <?php if (empty($books)): ?>
            <h2 style="color:#fff;text-align:center;width:100%;">No se encontraron libros disponibles.</h2>
        <?php else: ?>
            <?php foreach ($books as $book): ?>
                <div class="fullbook">
                    <div class="bookbox">
                        <div class="functionsbook">
                            <h3 class="statusbook"><?= htmlspecialchars($book['typeof']) ?></h3>
                        </div>
                        <div class="imagenbox">
                            <img src="<?= htmlspecialchars($book['bookpic']) ?>" alt="Libro publicado">
                        </div>
                    </div>
                    <div class="infolibro">
                        <h3 class="TituloLibro"><?= htmlspecialchars($book['name']) ?></h3>
                        <p style="font-size:0.95vw;color:black;margin:0 0 0.3vw 0;"><b>Autor:</b>
                            <?= htmlspecialchars($book['author']) ?></p>
                        <p style="font-size:0.95vw;color:#555;margin:0 0 0.3vw 0;"><b>Género:</b>
                            <?= htmlspecialchars($book['genre']) ?></p>
                        <p style="font-size:0.95vw;color:#555;margin:0 0 0.3vw 0;"><b>Editorial:</b>
                            <?= htmlspecialchars($book['editorial']) ?></p>
                        <p style="font-size:0.95vw;color:#555;margin:0 0 0.3vw 0;"><b>Publicado por:</b>
                            <?= htmlspecialchars($book['owner_name']) ?></p>
                        <?php if ($book['price'] !== null): ?>
                            <h4 class="PrecioLibro">$<?= htmlspecialchars($book['price']) ?></h4>
                        <?php elseif ($book['price'] == null): ?>
                            <h4 class="PrecioLibro">($) No aplica</h4>
                        <?php endif; ?>
                        <div class="AdquirirLibro">
                            <?php if ($is_logged_in && $book['ownerid'] != $_SESSION['user_id']): ?>
                                <a href="pickedbook.php?id=<?= $book['id'] ?>">Adquirir</a>
                            <?php elseif (!$is_logged_in): ?>
                                <a href="login.php">Inicia sesión para adquirir</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <style>
        .bookbox-container {
            display: flex;
            flex-wrap: wrap;
            gap: 2.2vw;
            justify-content: center;
            align-items: stretch;
            width: 100%;
            padding-top: 2vw;
            padding-bottom: 4vw;
            box-sizing: border-box;
        }

        .PrecioLibro {
            margin: 0 0 0.2vw 0;
            min-height: 1.5em;
            color: #222;
            font-size: 1vw;
            top: 0;
        }

        .fullbook {
            background: #fff;
            border-radius: 1vw;
            box-shadow: 0 0.5vw 2vw rgba(0, 0, 0, 0.08);
            width: 20.5vw;
            min-width: 180px;
            max-width: 98vw;
            display: flex;
            flex-direction: column;
            align-items: stretch;
            min-height: 32vw;
            margin-bottom: 2vw;
            overflow: hidden;
        }

        .bookbox {
            width: 100%;
            aspect-ratio: 1/1.2;
            border-radius: 1vw;
            background: linear-gradient(to bottom,
                    #ffffff 0%,
                    #ebebebff 95%);
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .imagenbox {
            width: 80%;
            height: 80%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: auto;
        }

        .imagenbox img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 1vw;
        }

        .infolibro {
            padding: 0.6vw 2vw 0.2vw 2vw;
            width: 100%;
            flex: 1 1 auto;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: flex-start;
            box-sizing: border-box;
            gap: 0;
        }

        .functionsbook {
            background-color: #000080;
            position: absolute;
            top: 1.2rem;
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: center;
            width: 55%;
            height: 12%;
            border-radius: 1rem;
        }


        .functionsbook img {
            width: 1.5rem;
        }

        .statusbook {
            font-size: 1rem;
            color: white;
        }

        .PrecioLibro {
            margin: 0;
            padding: 0;
        }

        .TituloLibro {
            font-size: 1.2vw;
            font-family: "HovesMedium";
            font-weight: bold;
            margin: 0 0 0.2vw 0;
            width: 100%;
            height: 20%;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-top: 0.2rem;
            color: black;
        }

        .AdquirirLibro {
            margin-top: 0.8vw;
            width: 100%;
            top: 0;
            display: flex;
            justify-content: flex-end;
        }

        .AdquirirLibro a {
            text-decoration: none;
            color: #fff;
            background-color: #000080;
            padding: 0.4vw 1vw;
            border-radius: 0.5vw;
            transition: background 0.8s;
            font-size: 1vw;
            margin-bottom: 1.8vw;
            font-family: "HovesDemiBold";
        }

        .AdquirirLibro a:hover {
            background-color: #fff;
            color: #000080;
        }

        h2 {
            margin-top: 3.5rem;
            margin-bottom: 1rem;
        }
    </style>

</body>

</html>