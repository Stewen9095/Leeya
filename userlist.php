<?php

require_once 'auth_functions.php';
require_once 'database.php';

$pdo = getDBConnection();
$view = isset($_GET['view']) ? $_GET['view'] : 'users'; // 'users' or 'books'
$books = getAllBooks();
$users = getUsersByRole('user');
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
        body {
            margin: 0;
            font-family: 'HovesDemiBold';
            background-color: #000;
            color: #fff;
        }

        header {
            width: 100%;
            background: linear-gradient(to bottom, #000 0%, #001aafff 80%);
            padding-top: 1rem;
            padding-bottom: 1rem;
        }

        nav {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: clamp(1rem, 3vw, 2.5rem);
            width: 75vw;
            max-width: 75vw;
            margin: auto;
            font-family: 'HovesExpandedBold';
            box-sizing: border-box;
        }

        .iconoimg {
            height: 3.5rem;
            width: auto;
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
            transition: background 0.8s;
        }

        .nav-btns .circle:hover {
            background: #000080;
        }

        .nav-btns img {
            width: 1.6rem;
            height: 1.6rem;
            object-fit: contain;
        }

        main {
            min-height: 70vh;
            padding: 2rem;
            max-width: 100vw;
            box-sizing: border-box;
        }

        main h1 {
            text-align: center;
            font-size: 2.5rem;
            margin-top: 0;
            margin-bottom: 2rem;
            color: #fff;
        }

        .table-container {
            background: #001aafff;
            border-radius: 1rem;
            padding: 1.5rem;
            overflow-x: auto;
            box-shadow: 0 0.5rem 2rem rgba(0, 0, 0, 0.3);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.95rem;
        }

        thead {
            background: #000080;
            color: #fff;
            font-weight: bold;
        }

        th {
            padding: 1rem;
            text-align: left;
            border-bottom: 2px solid #fff;
        }

        td {
            padding: 0.8rem 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        tbody tr:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        tbody tr:nth-child(even) {
            background: rgba(255, 255, 255, 0.02);
        }

        .status-active {
            background: #00a86b;
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

        .btn-view {
            background: #17a2b8;
            color: #fff;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 0.5rem;
            cursor: pointer;
            text-decoration: none;
            font-size: 0.9rem;
            transition: background 0.3s;
            display: inline-block;
        }

        .btn-view:hover {
            background: #138496;
        }

        .price-cell {
            font-weight: bold;
            color: #ffd700;
        }
        .table-tabs {
            display:flex;
            gap:0.5rem;
            justify-content:center;
            margin-bottom:1.2rem;
        }

        .table-tabs .tab {
            text-decoration:none;
            background:#001aafff;
            color:#fff;
            padding:0.5rem 1rem;
            border-radius:1rem;
            box-shadow:0 0.125rem 0.5rem #0002;
        }

        .table-tabs .tab.active {
            background:#000080;
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

    <header>
        <nav>
            <a href="adminpanel.php">
                <img src="img/icono.png" class="iconoimg" alt="Leeya icono">
            </a>
            <div class="nav-btns">
                <a href="explore.php">
                    <h3>EXPLORAR</h3>
                </a>
                <a href="adminreports.php">
                    <h3>REPORTES</h3>
                </a>
                <a href="logout.php">
                    <h3>CERRAR SESIÓN</h3>
                </a>
            </div>
        </nav>
    </header>

    <main>
        <h1>Panel de Administración - Gestión de <?= $view === 'users' ? 'Usuarios' : 'Libros' ?></h1>
        <div class="table-tabs">
            <a href="userlist.php?view=users" class="tab <?= $view === 'users' ? 'active' : '' ?>">Usuarios</a>
            <a href="userlist.php?view=books" class="tab <?= $view === 'books' ? 'active' : '' ?>">Libros</a>
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
                                <th>Descripción</th>
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
                                    <td><?= htmlspecialchars(mb_strimwidth($u['lildescription'], 0, 80, '...')) ?></td>
                                    <td>
                                        <a href="pickeduserAdmin.php?id=<?= $u['id'] ?>" class="btn-view">Ver Perfil</a>    
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
                                <th>Autor</th>
                                <th>Propietario</th>
                                <th>Tipo</th>
                                <th>Precio</th>
                                <th>Estado</th>
                                <th>Calidad</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($books as $book): ?>
                                <tr>
                                    <td><?= htmlspecialchars($book['id']) ?></td>
                                    <td><?= htmlspecialchars($book['name']) ?></td>
                                    <td><?= htmlspecialchars($book['author']) ?></td>
                                    <td><?= htmlspecialchars($book['owner_name']) ?></td>
                                    <td><?= htmlspecialchars($book['typeof']) ?></td>
                                    <td class="price-cell">
                                        <?php if ($book['price'] !== null): ?>
                                            $<?= htmlspecialchars($book['price']) ?>
                                        <?php else: ?>
                                            N/A
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($book['status'] == 1): ?>
                                            <span class="status-active">Disponible</span>
                                        <?php else: ?>
                                            <span class="status-inactive">No Disponible</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars($book['qstatus']) ?>/5</td>
                                    <td>
                                        <a href="pickedbook.php?id=<?= $book['id'] ?>" class="btn-view">Ver Detalles</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        <?php endif; ?>

    </main>

    <footer>
        <img src="img/icono.png" alt="Leeya logo">
        <p>Leeya: un espacio de acceso a la literatura y contenido bibliográfico dedicado a los estudiantes de la Universidad Distrital Francisco José de Caldas.</p>
        <p>© 2025 Leeya. Todos los derechos reservados.</p>
        <p>Un proyecto de la Universidad Distrital Francisco José de Caldas</p>
    </footer>

</body>

</html>
