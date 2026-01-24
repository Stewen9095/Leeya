<?php

session_start();

require_once 'auth_functions.php';
require_once 'database.php';

$is_logged_in = false;
$user_role = '';
$pdo = getDBConnection();

refreshSessionUser();
updateExpiredAuctions();

if (isLoggedIn()) {

    if (isset($_SESSION['user_id'])) {
        $is_logged_in = true;
        $user_name = htmlspecialchars($_SESSION['user_name'] ?? '');
        $user_role = htmlspecialchars($_SESSION['user_role'] ?? 'admin');
    }

}

if (isset($_SESSION['user_id'])) {
    if (!empty($_SESSION['user_role']) && $_SESSION['user_role'] === 'user') {
        header('Location: index.php');
        exit();
    } elseif ($_SESSION['user_role'] === 'banned') {
        header('Location: banned.php');
        exit();
    }
}

// Procesar acción de marcar reporte como chequeado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['check_report'])) {
    $report_id = intval($_POST['report_id'] ?? 0);
    if ($report_id > 0) {
        $result = markReportAsChecked($report_id);
    }
    header('Location: reports.php');
    exit();
}

$reports = getUncheckedReports();

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel | Leeya</title>
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
</head>

<body>

    <nav>

        <a href="index.php" class="image-logo">
            <div class="content">LEEYA</div>
        </a>
        <a href="explore.php" class="image-logo">
            <div class="content">EXPLORAR</div>
        </a>
        <a href="userlist.php" class="image-logo">
            <div class="content">USUARIOS</div>
        </a>
        <a href="logout.php" class="image-logo">
            <div class="content">CERRAR SESIÓN</div>
        </a>

    </nav>


    <style>
        main {
            max-width: 1440px;
            min-width: 200px;
            width: 96%;
            height: auto;
            display: flex;
            flex-direction: column;
            margin: 2.8rem auto 0 auto;
            padding: 3.5rem 0 0 0;
            justify-content: center;
            align-items: center;
        }

        .cajareporte {
            width: 100%;
            background-color: #d8d8d888;
            border: 1px solid rgba(99, 99, 99, 0.37);
            border-radius: clamp(5px, 1.5vw, 12px);
            display: flex;
            justify-content: center;
            text-align: center;
            color: #333333;
            overflow: visible;
        }

        .table-container {
            border-radius: 1rem;
            padding: 1rem;
            box-sizing: border-box;
            overflow-x: auto;
            overflow-y: auto;
            width: 100%;
            margin: clamp(0.5rem, 1.5vw, 1rem);
            border: 1px solid rgba(99, 99, 99, 0.37);
            backdrop-filter: blur(5px);
            background-color: transparent;
            max-height: 60vh;
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
        }

        th {
            padding: 0.8rem 1rem;
            text-align: left;
            color: #333333;
            background-color: #d8d8d888;
        }

        td {
            padding: 0.6rem 1rem;
            color: #333333;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);

            a {
                color: #333333;
                text-decoration: none;
            }

            button {
                border: 1px solid rgba(99, 99, 99, 0.37);
                background-color: #64646425;
                color: #fff;
                padding: 0.5rem 1rem;
                border-radius: 0.5rem;
                cursor: pointer;
                font-size: 0.9rem;
                font-family: "HovesDemiBold";
                color: #333333;
                transition: 3s;
                background-color: #08083069;
            }

            button:hover {

                background: rgba(83, 95, 255, 0.34);

            }
        }
    </style>

    <main>

        <div class="cajareporte">
            <?php if (empty($reports)): ?>
                <p>No hay reportes pendientes de revisar.</p>
            <?php else: ?>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Motivo</th>
                                <th>Descripción</th>
                                <th>Fecha</th>
                                <th>Reporter</th>
                                <th>Reported</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reports as $report): ?>
                                <tr>
                                    <td><?= htmlspecialchars($report['id']) ?></td>
                                    <td><?= htmlspecialchars($report['motive']) ?></td>
                                    <td><?= htmlspecialchars(substr($report['description'], 0, 70)) ?>...</td>
                                    <td><?= htmlspecialchars($report['datereport']) ?></td>
                                    <td>
                                        <a href="pickeduser.php?id=<?= $report['reporter_id'] ?>">
                                            <?= htmlspecialchars($report['reporter_name']) ?>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="pickeduser.php?id=<?= $report['reported_id'] ?>">
                                            <?= htmlspecialchars($report['reported_name']) ?>
                                        </a>
                                    </td>
                                    <td>
                                        <form method="post" style="display:inline;">
                                            <input type="hidden" name="report_id" value="<?= $report['id'] ?>">
                                            <button type="submit" name="check_report">Marcar como revisado</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </main>

</body>

</html>