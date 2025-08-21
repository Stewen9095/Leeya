<?
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="img/icon.png">
    <title>Mi cuenta</title>
</head>
<body>
    
</body>
</html>
<!DOCTYPE html>
<html lang="es">
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
            font-family: 'HovesDemiBoldItalic';
            margin: 0;
        }
        .profile-container {
            max-width: 75rem;
            margin: 5.8rem auto 2.9rem auto;
            background: #0a0a23;
            border-radius: 2rem;
            box-shadow: 0 0 32px #0008;
            padding: 2.5rem 2rem 2rem 2rem;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #001aaf;
            background: #222;
            margin-bottom: 1.2rem;
        }
        .profile-name {
            font-size: 2rem;
            font-family: 'HovesBold', Arial, sans-serif;
            margin-bottom: 0.3rem;
        }
        .profile-email {
            font-size: 1.1rem;
            color: #b0b0ff;
            margin-bottom: 1.2rem;
        }
        .profile-info {
            width: 100%;
            margin-top: 1.2rem;
        }
        .profile-info-item {
            display: flex;
            justify-content: space-between;
            padding: 0.6rem 0;
            border-bottom: 1px solid #222a;
            font-size: 1.05rem;
        }
        .profile-actions {
            margin-top: 2rem;
            display: flex;
            gap: 1rem;
        }
        .profile-btn {
            background: #001aaf;
            color: #fff;
            border: none;
            border-radius: 1.2rem;
            padding: 0.6rem 1.5rem;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.3s;
        }
        .profile-btn:hover {
            background: #000080;
        }
    </style>
</head>
<body>
    <a href="index.php" style="color:#b0b0ff;position:absolute;left:2rem;top:2rem;text-decoration:none;font-size:1.1rem;">&larr; Volver al inicio</a>
    <div class="profile-container">
        <img src="img/user.png" alt="Foto de perfil" class="profile-avatar">
        <div class="profile-name"><?php echo htmlspecialchars($user['name']); ?></div>
        <div class="profile-email"><?php echo htmlspecialchars($user['email']); ?></div>
        <div class="profile-info">
            <div class="profile-info-item"><span>Rol:</span> <span><?php echo htmlspecialchars($user['role']); ?></span></div>
            <!-- Puedes agregar más datos aquí si los tienes en la sesión, por ejemplo localidad, fecha de registro, etc. -->
        </div>
        <div class="profile-actions">
            <a href="logout.php" class="profile-btn">Cerrar sesión</a>
            <!-- <a href="#" class="profile-btn">Editar perfil</a> -->
        </div>
    </div>
</body>
</html>