<?php

require_once 'auth_functions.php';
require_once 'database.php';

$pdo = getDBConnection();
$view = isset($_GET['view']) ? $_GET['view'] : 'users'; // 'users' or 'books'
$books = getAllBooks();
$users = getAllUsersForAdmin();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Libros | Leeya</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="img/icon.png" type="image/png">

    <style>
        html {
            background: white;
            margin: 0;
            padding: 0;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'HovesDemiBold';
            background: white;
        }

        nav {
            position: fixed;
            max-width: 1440px;
            min-width: 200px;
            width: fit-content;
            height: auto;
            background-color: #08083069;
            backdrop-filter: blur(8px);
            display: inline-flex;
            justify-content: center;
            align-items: stretch;
            box-sizing: border-box;
            left: 0;
            right: 0;
            margin: auto;
            border: 1px solid rgba(99, 99, 99, 0.37);
            border-radius: 1rem;
            font-size: 15px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            z-index: 5;
        }

        nav a {
            box-sizing: border-box;
            margin-inline: auto;
            inset-inline: 0;
            width: fit-content;
            padding: .2rem .5rem;
            margin: .3rem .3rem .3rem .3rem;
            border: 1px solid rgba(99, 99, 99, 0.6);
            backdrop-filter: blur(5px);
            background-color: #d8d8d888;
            border-radius: .6rem;
            color: #333333;
            text-decoration: none;
            min-width: 140px;
            overflow: hidden;
            max-width: 18%;
            max-height: 30px;

            .content {
                box-sizing: border-box;
                margin: 0;
                padding: 0;
                text-align: center;
            }

        }

        /* Cel */
        @media (max-width: 750px) {

            nav {
                position: static;
                display: flex;
                margin-top: 30px;
                flex-direction: column;
                font-size: 13px;
                border-radius: 5px;
                padding: 2px 0;
                width: 80%;
                align-items: center;

                a {
                    margin: .1rem;
                    padding: 2px 10px;
                    width: 98%;
                    height: 35px;
                    border-radius: 5px;
                    display: flex;
                    justify-content: center;
                    align-items: stretch;
                    max-width: 100%;
                    min-height: 30px;
                }

            }

        }
    </style>

    <style>
        .table-container {
            border-radius: 1rem;
            padding: 1.5rem;
            box-sizing: border-box;
            overflow-x: auto;
            box-shadow: 0 0.5rem 2rem rgba(0, 0, 0, 0.3);
            width: 98%;
            margin: clamp(1.2rem, 5vh, 2rem);
            border: 1px solid rgba(99, 99, 99, 0.37);
            backdrop-filter: blur(5px);
            background-color: #d8d8d888;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.95rem;
        }

        thead {
            background-color: #64646425;
            color: #333333;
            font-weight: bold;
            box-sizing: border-box;
        }

        th {
            padding: 1rem;
            text-align: left;
            border-bottom: 2px solid #fff;
            color: #333333;
            background-color: #d8d8d888;
        }

        td {
            padding: 0.8rem 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            color: #333333;
        }

        tbody tr:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        tbody tr:nth-child(even) {
            background: rgba(255, 255, 255, 0.02);
        }

        .status-active {
            background-color: #0819b6d0;
            color: #fff;
            padding: 0.3rem 0.8rem;
            border-radius: 0.5rem;
            font-size: 0.85rem;
            display: inline-block;
        }

        .status-inactive {
            background: #d9534f;
            color: #fff;
            padding: 0.3rem 0.8rem;
            border-radius: 0.5rem;
            font-size: 0.85rem;
            display: inline-block;
        }

        .status-banned {
            background-color: #e20808d0;
            color: #fff;
            padding: 0.3rem 0.8rem;
            border-radius: 0.5rem;
            font-size: 0.85rem;
            display: inline-block;
        }

        .owner-banned {
            background: #e20808d0;
            color: #fff;
            padding: 0.3rem 0.8rem;
            border-radius: 0.5rem;
            font-size: 0.85rem;
            display: inline-block;
            font-weight: bold;
        }

        .btn-view {
            background-color: #08083069;
            color: #333333;
            border: 1px solid rgba(99, 99, 99, 0.37);
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            cursor: pointer;
            text-decoration: none;
            font-size: 0.9rem;
            transition: background 0.3s;
            display: inline-block;
            box-sizing: border-box;
        }

        .btn-view:hover {
            background: #333333;
        }

        .price-cell {
            font-weight: bold;
            color: #ffd700;
        }

        .table-tabs {
            display: flex;
            flex-wrap: wrap;
            width: 80%;
            justify-content: center;
            gap: 0.5rem;
            justify-content: center;
            margin-top: .8rem;
        }

        .table-tabs .tab {
            flex: 1 1 400px;
            text-align: center;
            text-decoration: none;
            border: 1px solid rgba(99, 99, 99, 0.37);
            backdrop-filter: blur(5px);
            background-color: #08083069;
            border-radius: .6rem;
            color: #333333;
            padding: 0.2rem 1rem;
            border-radius: 1rem;
            box-shadow: 0 0.125rem 0.5rem #0002;
        }

        .table-tabs .tab.active {
            background: #000080;
        }

        footer {
            text-align: center;
            background: linear-gradient(to top, #000 0%, #001aafff 90%);
            color: #fff;
            padding: 3rem 1rem 2rem 1rem;
            font-family: 'HovesMedium';
            font-size: 1rem;
        }

        footer img {
            height: 3rem;
            display: block;
            margin: 0 auto 1rem auto;
        }

        footer p {
            margin: 0.5rem 0;
        }

        .empty-message {
            text-align: center;
            color: #fff;
            padding: 3rem;
            font-size: 1.2rem;
        }
    </style>
</head>

<body>

    <nav>

        <a href="index.php" class="image-logo">
            <div class="content">LEEYA</div>
        </a>
        <a href="explore.php" class="image-logo">
            <div class="content">EXPLORAR</div>
        </a>
        <a href="reports.php" class="image-logo">
            <div class="content">REPORTES</div>
        </a>
        <a href="logout.php" class="image-logo">
            <div class="content">CERRAR SESIÓN</div>
        </a>

    </nav>

    <style>
        main {
            max-width: 1440px;
            min-width: 200px;
            width: 95%;
            height: auto;
            display: flex;
            flex-direction: column;
            margin: 2.8rem auto 0 auto;
            padding: 5rem 0 0 0;
            justify-content: center;
            align-items: center;
        }

        @media(max-width: 750px) {

            main {
                flex-direction: column;
                margin: 2rem auto 0 auto;
                width: 94%;
                height: auto;
                padding: 0;
            }
        }
    </style>

    <main>
        <div class="table-tabs">
            <a href="userlist.php?view=users" class="tab">USUARIOS</a>
            <a href="userlist.php?view=books" class="tab">LIBROS</a>
        </div>

        <?php if ($view === 'users'): ?>
            <?php if (empty($users)): ?>
                <div class="empty-message">
                    <p>No hay usuarios con rol 'user'.</p>
                </div>
            <?php else: ?>

                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Localidad</th>
                                <th>Registro</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $u): ?>
                                <tr>
                                    <td><?= htmlspecialchars($u['id']) ?></td>
                                    <td><?= htmlspecialchars($u['name']) ?></td>
                                    <td><?= htmlspecialchars($u['email']) ?></td>
                                    <td><?= htmlspecialchars($u['location']) ?></td>
                                    <td><?= htmlspecialchars($u['signdate']) ?></td>
                                    <td>
                                        <?php if ($u['userrole'] === 'banned'): ?>
                                            <span class="status-banned">BANNEADO</span>
                                        <?php else: ?>
                                            <span class="status-active">ACTIVO</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="pickeduser.php?id=<?= $u['id'] ?>" class="btn-view">VER PERFIL</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <?php if (empty($books)): ?>
                <div class="empty-message">
                    <p>No hay libros en la base de datos.</p>
                </div>
            <?php else: ?>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Usuario</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($books as $book): ?>
                                <tr>
                                    <td><?= htmlspecialchars($book['id']) ?></td>
                                    <td><?= htmlspecialchars($book['name']) ?></td>
                                    <td>
                                        <?php if ($book['owner_role'] === 'banned'): ?>
                                            <span class="owner-banned">DUEÑO BANNEADO</span>
                                        <?php else: ?>
                                            <?= htmlspecialchars($book['owner_name']) ?>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($book['status'] == 1): ?>
                                            <span class="status-active">Disponible</span>
                                        <?php else: ?>
                                            <span class="status-inactive">No Disponible</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($book['status'] == 1): ?>
                                            <a href="pickedbook.php?id=<?= $book['id'] ?>" class="btn-view">VER DETALLES</a>
                                        <?php else: ?>
                                            <a href="adminpanel.php" class="btn-view">NO DISPONIBLE</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        <?php endif; ?>

    </main>

</body>

</html>