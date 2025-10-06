<?php

session_start();

require_once 'auth_functions.php';
require_once 'database.php';

$is_logged_in = false;
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

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $is_logged_in) {
    $ownerid = $_SESSION['user_id'];
    $name = $_POST['name'] ?? '';
    $author = $_POST['author'] ?? '';
    $genre = $_POST['genero'] ?? '';
    $editorial = $_POST['editorial'] ?? '';
    $description = $_POST['description'] ?? '';
    $qstatus = $_POST['status'] ?? 0;
    $bookpic = $_POST['imagen'] ?? '';
    $typeof = $_POST['trx'] ?? '';
    $status = 1; // Disponible al ser recién publicado
    $price = isset($_POST['monto']) && $_POST['monto'] !== '' ? $_POST['monto'] : null;

    $limdate = isset($_POST['fecha']) && $_POST['fecha'] !== '' ? $_POST['fecha'] : null;

    if (empty($name) || empty($author) || empty($genre) || empty($editorial) || empty($description) || empty($bookpic) || empty($typeof) || $qstatus === '') {
        $error = 'Completa todos los campos obligatorios.';
    } elseif ($typeof === "Subasta" && !$limdate) {
        $error = 'Debes ingresar una fecha límite para la subasta.';
    } else {
        $result = createBook($ownerid, $name, $author, $genre, $editorial, $description, $qstatus, $bookpic, $typeof, $status, $price, $limdate);
        if ($result['success']) {
            $_SESSION['newbook_message'] = $result['message'];
            header('Location: newbook.php');
            exit();
        } else {
            $error = $result['message'];
        }
    }
}

// --- Y agrega esto donde inicializas $message ---
$message = '';
if (isset($_SESSION['newbook_message'])) {
    $message = $_SESSION['newbook_message'];
    unset($_SESSION['newbook_message']);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publicar libro</title>
    <link rel="icon" href="img/icon.png">
    </link>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <header>
        <nav>
            <a href="index.php">
                <img src="img/icono.png" class="iconoimg" alt="Leeya icono">
            </a>
            <div class="nav-btns">

                <a href="explore.php">
                    <h3>EXPLORAR</h3>
                </a>

                <?php if ($is_logged_in): ?>


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

        .form-whole {
            max-width: 88%;
            margin: 1.5rem auto;
            height: 27.8rem;
            max-height: 27.8rem;
            margin-top: -1.2rem;
            background: linear-gradient(to bottom,
                    #001aafff 0%,
                    #000080 55%);
            border-radius: 2rem;
            box-shadow: 0 0 0.5rem rgba(240, 240, 240, 0.05);
            padding: 1.5rem 1.8rem 1.5rem 1.8rem;
            display: flex;
            flex-direction: row;
            align-items: center;
            gap: 1.8rem;
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
        }

        .form-group label {
            font-weight: 600;
            font-family: "HovesExpandedBold";
            color: white;
            font-size: clamp(0.75rem, 1.5vw, 0.95rem);
            margin-bottom: 0.15rem;
        }

        .form-group input {
            padding: 0.4rem 0.8rem;
            border-radius: 1rem;
            border: 1.8px solid #001aaf;
            font-size: clamp(0.8rem, 1.6vw, 1rem);
            outline: none;
            transition: all 0.25s ease;
        }

        .form-group input:focus {
            border-color: #0000ff;
            box-shadow: 0 0 0.3rem rgba(0, 0, 255, 0.3);
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
            font-family: "HovesExpandedBoldItalic";
            font-size: clamp(0.8rem, 1.5vw, 1rem);
            cursor: pointer;
            border: none;
            transition: all 0.5s ease;
            background-color: white;
            color: #000080;
            border: none;
            border-radius: var(--radius, 8px);
            font-family: "HovesExpandedBold";
            transition: background-color 0.5s, transform 0.5s;
        }

        .btn-save:hover {
            background: #000080;
            color: white;
        }

        .btn-cancel:hover {
            background: #000080;
            color: white;
        }

        .bookpic {
            text-align: center;
            width: 30%;
            height: 62%;
            background-color: #fff;
            border-radius: 2rem;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            position: relative;
        }

        .preview {
            width: 100%;
            height: 25%;
            margin-top: 0%;
            background: linear-gradient(to bottom,
                    #000080 0%,
                    #001aafff 55%);
            border-radius: 1rem;
            align-items: center;
            position: absolute;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.5);
        }

        .preview-text {
            width: 100%;
            height: 100%;
            /*background-color: blue;*/
            border-radius: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 0.01rem;
            align-items: center;
            line-height: 1.7;
            overflow: hidden;
        }

        .preview-text p {
            font-family: "HovesExtraLight";
            max-width: 95%;
            max-height: 95%;
            margin: -0.2rem;
            padding: 0;
            font-size: clamp(0.2rem, 2vw, 0.78rem);
            color: white;
            align-items: center;
        }

        .realpic {
            width: 100%;
            height: 88%;
            margin-top: 0%;
            background: white;
            align-items: center;
            overflow: hidden;
            border-radius: 1.5rem 1.5rem 0 0;
        }

        .realpic img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            border-radius: 1.5rem 1.5rem 0 0;
            max-width: 100%;
            max-height: 100%;
            image-rendering: auto;
        }

        .messages-back {
            width: 100%;
            height: 15%;
            margin-top: 0rem;
            background: linear-gradient(to bottom,
                    #000080 0%,
                    #001aafff 55%);
            border-radius: 0 0 1rem 1rem;
            align-items: center;
            position: absolute;
            bottom: 0;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.5);
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

        .error-message {
            background: #fee;
            color: #c53030;
            padding: 0.75rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            font-size: 0.9rem;
            border: 1px solid #fed7d7;
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
    </style>

    <div class="form-whole">

        <div class="bookinfo"> <!-- Caja izquierda -->

            <h2>Publica tu libro</h2>

            <form class="book-form" method="POST" action="">
                <div class="form-group">
                    <label for="name">Título del libro</label>
                    <input type="text" id="name" name="name" placeholder="Ej: Cien años de soledad" required>
                </div>

                <div class="form-group">
                    <label for="author">Autor</label>
                    <input type="text" id="author" name="author" placeholder="Ej: Gabriel García Márquez" required>
                </div>

                <div class="form-group">
                    <label for="genre">Descripcion</label>
                    <input type="text" id="description" name="description" placeholder="Describe tu publicacion"
                        required>
                </div>

                <div class="form-group">
                    <label for="editorial">Editorial</label>
                    <input type="text" id="editorial" name="editorial" placeholder="Ej: Panamericana" required>
                </div>

                <div class="form-group">
                    <label for="editorial">Imagen del libro</label>
                    <input type="text" id="imagen" name="imagen" placeholder="Ingresa el link de tu imagen" required>
                </div>

                <div class="form-group">
                    <label for="editorial">Genero</label>
                    <input type="text" id="genero" name="genero" placeholder="Ej: Realismo magico" required>
                </div>

                <div class="form-group">
                    <label for="status">Estado del libro</label>
                    <select id="status" name="status" required>
                        <option value="">Selecciona un estado</option>
                        <option value="0">0 - Muy deteriorado</option>
                        <option value="1">1 - Dañado</option>
                        <option value="2">2 - Regular</option>
                        <option value="3">3 - Bueno</option>
                        <option value="4">4 - Muy bueno</option>
                        <option value="5">5 - Como nuevo</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="trx">Tipo de transaccion</label>
                    <select id="trx" name="trx" required>
                        <option value="">¿Que deseas realizar?</option>
                        <option value="Donacion">Donacion</option>
                        <option value="Venta">Venta</option>
                        <option value="Intercambio">Intercambio</option>
                        <option value="Subasta">Subasta</option>
                    </select>
                </div>

                <div class="form-group" id="monto-group" style="display: none;">
                    <label for="monto">Monto</label>
                    <input type="number" id="monto" name="monto" placeholder="Ingresa el monto del libro">
                </div>

                <div class="form-group" id="fecha-group" style="display: none;">
                    <label for="monto">Fecha limite</label>
                    <input type="date" id="fecha" name="fecha" placeholder="Fecha limite">
                </div>

                <div class="form-buttons">
                    <button type="submit" class="btn-save">Guardar</button>
                    <button type="reset" class="btn-cancel">Limpiar</button>
                </div>
            </form>
        </div>

        <div class="bookpic"> <!-- Caja derecha -->

            <div class="preview"> <!-- Caja azul -->

                <div class="preview-text"> <!-- Reservada para el texto de preview -->
                    <p></p>
                    <p></p>
                    <p></p>
                </div>

            </div>

            <div class="realpic"> <!-- Reservada para la imagen del libro -->
                <img src="" alt="Imagen del libro">
            </div>

            <div class="messages-back">
                <?php if ($message): ?>
                    <div class="success-message"><?= htmlspecialchars($message) ?></div>
                <?php endif; ?>
                <?php if ($error): ?>
                    <div class="error-message"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
            </div>

        </div>

    </div>

    <script>
        // --- Mostrar campo de monto según tipo de transacción ---
        const trxSelect = document.getElementById("trx");
        const montoGroup = document.getElementById("monto-group");
        const montoInput = document.getElementById("monto");
        const fechaGroup = document.getElementById("fecha-group");
        const fechaInput = document.getElementById("fecha");


        trxSelect.addEventListener("change", () => {
            const valor = trxSelect.value;
            if (valor === "Venta") {
                montoGroup.style.display = "flex";
                montoInput.required = true;
                montoInput.placeholder = "Ingresa el monto";
                fechaGroup.style.display = "none";
                fechaInput.required = false;
                fechaInput.value = "";
            } else if (valor === "Subasta") {
                montoGroup.style.display = "flex";
                montoInput.required = true;
                montoInput.placeholder = "Ingresa el monto base";
                fechaGroup.style.display = "flex";
                fechaInput.required = true;
            } else {
                montoGroup.style.display = "none";
                montoInput.required = false;
                montoInput.value = "";
                fechaGroup.style.display = "none";
                fechaInput.required = false;
                fechaInput.value = "";
            }
        });

        // --- Actualizar vista previa en tiempo real ---
        const titleInput = document.getElementById("name");
        const authorInput = document.getElementById("author");
        const statusSelect = document.getElementById("status");
        const imageInput = document.getElementById("imagen");

        const previewTitle = document.querySelector(".preview-text p:nth-child(1)");
        const previewAuthor = document.querySelector(".preview-text p:nth-child(2)");
        const previewStatus = document.querySelector(".preview-text p:nth-child(3)");
        const previewImage = document.querySelector(".realpic img");

        // Función para convertir número en estrellas
        function getStars(num) {
            if (!num) return "—";
            num = parseInt(num);
            let stars = "";
            for (let i = 0; i < 5; i++) {
                stars += i < num ? "⭐" : " ☆ ";
            }
            return stars;
        }

        // Función general para actualizar preview
        function updatePreview() {
            previewTitle.textContent = `Título: ${titleInput.value.trim() || "—"}`;
            previewAuthor.textContent = `Autor: ${authorInput.value.trim() || "—"}`;

            const stars = getStars(statusSelect.value);
            previewStatus.textContent = `Estado: ${stars}`;

            const link = imageInput.value.trim();
            previewImage.src = link
                ? link
                : "https://laud.udistrital.edu.co/sites/default/files/imagen-noticia/2022-09/laud-edificio-lectus-facultad-tecnologica-davidmoraconstructor%20%285%29.jpeg";
        }

        // Actualizar en cada cambio
        titleInput.addEventListener("input", updatePreview);
        authorInput.addEventListener("input", updatePreview);
        statusSelect.addEventListener("change", updatePreview);
        imageInput.addEventListener("input", updatePreview);

        // Inicializar al cargar la página
        updatePreview();
    </script>




</body>

</html>