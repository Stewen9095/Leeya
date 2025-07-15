<?php
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leeya</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="img/icono.png" type="image/png">
    <style>
        body { margin:0; font-family: Arial, sans-serif; background:#000; }
        header { background: transparent; box-shadow: none; }
        nav { display: flex; align-items: center; justify-content: center; gap: 2rem; padding: 2rem 0 1rem 0; }
        .iconoimg { width: 70px; height: 48px; margin-right: 1.5rem; }
        .nav-btns { display: flex; gap: 1.5rem; align-items: center; }
        .nav-btns a {
            text-decoration: none;
            background: #001AAF;
            color: #fff;
            font-weight: bold;
            font-size: 1.1rem;
            border-radius: 20px;
            padding: 0.5rem 1.5rem;
            box-shadow: 0 2px 8px #0002;
            transition: background 0.2s;
            display: flex;
            align-items: center;
        }
        .nav-btns a:hover { background: #000080; }
        .nav-btns h3 { margin: 0; display: inline; }
        .nav-btns .circle {
            width: 36px; height: 36px;
            border-radius: 50%;
            background: #eee;
            display: flex; align-items: center; justify-content: center;
        }
        .nav-btns img { width: 28px; height: 28px; border-radius: 50%; }
        .paneles { display: flex; gap: 2rem; justify-content: center; margin: 2rem 0; }
        .panel {
            flex:1; min-width: 300px; height: 180px; border-radius: 18px;
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            position: relative; color: #fff; font-weight: bold; font-size: 1.2rem;
        }
        .panel1 { background: #001AAF; }
        .panel2 { background: #000080; }
        .panel img { max-width: 60px; max-height: 60px; margin-top: 1rem; }
        .panel-text { text-align: center; font-size: 1.1rem; margin-bottom: 0.5rem; }
        .carrusel-container { max-width: 1100px; margin: 2rem auto; }
        .carrusel-titulo {
            font-size: 1.2rem;
            color: #fff;
            margin-bottom: 1rem;
            text-align: center;
            font-weight: bold;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .carrusel { display: flex; overflow-x: auto; gap: 1.5rem; padding-bottom: 1rem; }
        .libro { min-width: 140px; background: #fff; border-radius: 10px; box-shadow: 0 2px 8px #0001; text-align: center; padding: 1rem 0.5rem; }
        .libro img { width: 80px; height: 110px; object-fit: cover; border-radius: 6px; margin-bottom: 0.5rem; }
        .libro-nombre { font-size: 1rem; color: #222; font-weight: bold; }
        @media (max-width: 700px) {
            .paneles { flex-direction: column; gap: 1rem; }
            nav { flex-direction: column; gap: 1rem; }
        }
    </style>
// ...existing code...
    <header>
        <nav>
            <img src="img/icono.png" class="iconoimg" alt="Leeya icono">
            <div class="nav-btns">
                <a href="#"><h3>EXPLORAR</h3></a>
                <a href="#"><h3>+</h3></a>
                <a href="#"><h3>MIS LIBROS</h3></a>
                <span class="circle">
                    <img src="img/user.png" alt="Usuario">
                </span>
            </div>
        </nav>
    </header>

    <main>
        <div class="paneles">
            <div class="panel panel1">
                <div class="panel-text">LIBROS AL ALCANCE DE TODOS</div>
                <img src="img/panel1.png" alt="Panel 1">
            </div>
            <div class="panel panel2">
                <img src="img/panel2.png" alt="Panel 2">
                <div class="panel-text">PUBLICA O ADQUIERE TUS LIBROS HOY</div>
            </div>
        </div>

        <div class="carrusel-container">
            <div class="carrusel-titulo">PUBLICACIONES RECIENTES</div>
            <div class="carrusel">
                <?php
                $libros = [
                    ['nombre' => 'Cien años de soledad', 'img' => 'img/libro1.jpg'],
                    ['nombre' => 'Rayuela', 'img' => 'img/libro2.jpg'],
                    ['nombre' => 'El amor en los tiempos del cólera', 'img' => 'img/libro3.jpg'],
                    ['nombre' => 'Pedro Páramo', 'img' => 'img/libro4.jpg'],
                    ['nombre' => 'Don Quijote', 'img' => 'img/libro5.jpg'],
                    ['nombre' => 'La sombra del viento', 'img' => 'img/libro6.jpg'],
                    ['nombre' => 'Ficciones', 'img' => 'img/libro7.jpg'],
                    ['nombre' => 'El túnel', 'img' => 'img/libro8.jpg'],
                    ['nombre' => 'Aura', 'img' => 'img/libro9.jpg'],
                    ['nombre' => 'La casa de los espíritus', 'img' => 'img/libro10.jpg'],
                ];
                foreach ($libros as $libro): ?>
                    <div class="libro">
                        <img src="<?= htmlspecialchars($libro['img']) ?>" alt="<?= htmlspecialchars($libro['nombre']) ?>">
                        <div class="libro-nombre"><?= htmlspecialchars($libro['nombre']) ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>
</body>
</html>