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

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil | Leeya</title>
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

    <div class="profile-container">

        <style>
            .profile-container {
                padding-bottom: 2rem;
                border-radius: 1.25rem;
            }

            .welcome {
                font-family: 'HovesBold';
                font-size: 3rem;
                margin-bottom: -1.8rem;
                margin-top: 0.5rem;
            }

            .since {
                padding-bottom: 1rem;
            }

            .infotext {
                margin-bottom: 0rem;
                margin-top: 0rem;
                text-align: center;
            }

            .infotextfinal {
                margin-top: 0;
            }

            .dataUser {
                text-align: center;
                width: 50%;
                height: 80%;
                /*background-color: #fff;*/
                border-radius: 2rem;
                padding-top: 1rem;
                padding-bottom: 1rem;
                margin-left: 2rem;
            }

            .userChanges {

                margin-left: -5rem;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                text-align: center;
                width: 50%;
                height: 92%;
                /*background-color: #fff;*/
                border-radius: 1.25rem;
                gap: 0.8rem;
                padding-top: 1.2rem;

            }


            .profile-container {
                padding-left: -2rem;
                padding-right: -2rem;
            }

            .functions {
                text-decoration: none;
                background: #001aafff 0%;
                color: #fff;
                text-align: center;
                font-size: 1.2rem;
                border-radius: 2rem;
                padding: 0.5rem 1rem;
                box-shadow: 0 0.125rem 0.5rem rgba(8, 4, 253, 0.13);
                transition: background 0.5s;
                display: flex;
                align-items: center;
                justify-content: center;
                width: 80%;
                max-width: 25rem;
            }


            .functions:hover {
                background: #000080 95%;
            }
        </style>

        <div class="dataUser">

            <h1 class="welcome">Bienvenido, <?php echo htmlspecialchars(explode(' ', $_SESSION['user_name'])[0]); ?>
            </h1>
            <p class="since">Usuario activo desde:
                <?php echo htmlspecialchars(explode(' ', $_SESSION['user_signdate'])[0]); ?>
            </p>
            <p class="infotext">Correo electrónico de contacto:
                <?php echo htmlspecialchars(explode(' ', $_SESSION['user_email'])[0]); ?>
            </p>
            <p class="infotext">Ubicación: <?php echo htmlspecialchars($_SESSION['user_location']); ?></p>
            <?php
            $user_description = htmlspecialchars($_SESSION['user_description']);
            if ($_SESSION['user_description'] == '') {
                ?>
                <p class="infotextfinal">Aún no cuentas con una descripción</p>
                <?php
            } else {
                ?>
                <p class="infotextfinal">Tu descripción: <?php echo htmlspecialchars($user_description); ?>

                    <?php
            }
            ?>

        </div>

        <!-- Diff divs -->

        <div class="userChanges">
            <a class="functions" href="changePassword.php">Cambiar contraseña</a>
            <a class="functions" href="changeLocation.php">Cambiar localidad</a>
            <a class="functions" href="changeDescription.php">Cambiar descripción</a>
            <a class="functions" href="logout.php">Cerrar sesión</a>
        </div>

    </div>

    <br>

    <div class="profile-catalog">

        <?php

        $books = [];
        if ($is_logged_in && isset($_SESSION['user_id'])) {
            $books = getBooksByUserId($_SESSION['user_id']);
        }
        ?>

        <div class="bookbox-container">
            <?php if (empty($books)): ?>
                <h2 style="color:#fff;text-align:center;width:100%;">No tienes libros en tu catalogo</h2>
            <?php else: ?>
                <h1 style="color:#fff;text-align:center;width:100%;">Mi catalogo</h1>
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
                            <?php if ($book['price'] !== null): ?>
                                <h4 class="PrecioLibro">$<?= htmlspecialchars($book['price']) ?></h4>
                            <?php elseif ($book['price'] == null): ?>
                                <h4 class="PrecioLibro">($) No aplica</h4>
                            <?php endif; ?>
                            <div class="AdquirirLibro">
                                <a href="pickedbook.php?id=<?= $book['id'] ?>">Ver info</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

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
        </div>

    </div>

</body>

</html>