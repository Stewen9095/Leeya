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
}else{
    header('Location: index.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publicar libro</title>
    <link rel="icon" href="img/icon.png"></link>
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

                    <a href="mybooks.php">
                        <h3>MIS LIBROS</h3>
                    </a>


                <?php elseif (!$is_logged_in): ?>

                    <a href="login.php">
                        <h3>INICIAR SESIÓN</h3>
                    </a>

                <?php endif; ?>

                <?php if ($is_logged_in): ?>

                    <a class="circle" href="#">
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

        .form-whole{           
            max-width: 82%;
            margin: 1.5rem auto;
            height: 27.8rem;
            max-height: 27.8rem;
            margin-top:-1.2rem;
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

        .bookinfo{
            text-align: center;
            width: 65%;
            height: 96%;
            background-color: #fff;
            border-radius: 2rem;
            align-items: center;
        }

        .bookpic{
            text-align: center;
            width: 35%;
            height: 96%;
            background-color: #fff;
            border-radius: 2rem;
            display: flex;
            flex-direction: column;
            align-items: flex-start;            
        }

        .preview{
            width:100%;
            height: 25%;
            margin-top: 0%;
            background: linear-gradient(to bottom,
                        #000080 0%,
                        #001aafff 55%);
            border-radius: 1.6rem;
            align-items: center;
        }

        .preview-text{
            width: 100%;
            height: 100%;
            /*background-color: blue;*/
            border-radius: 3rem;
            display: flex; 
            flex-direction: column;
            justify-content: center;   
            gap: 0.01rem;        
            align-items: center; 
        }

        .preview-text p{
            max-width: 95%;
            max-height: 95%;
            margin:-0.2rem;
            padding: 0;
            font-size: clamp(0.2rem, 2vw, 1rem); 
            color: white;
            align-items: center;
        }

    </style>

    <div class="form-whole">

        <div class="bookinfo">
        </div>

        <div class="bookpic">

            <div class="preview">

                <div class="preview-text">
                    <p>Titulo: 100 anos de sobriedad</p>
                    <p>Autor: Gabriel garcia marquez </p>
                    <p>Estado: 5</p>
                </div>

            </div>

            <div>

            </div>
            
        </div>

    </div>

</body>
</html>