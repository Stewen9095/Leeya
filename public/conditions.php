<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Condiciones de Uso</title>
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
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .ban-container {
            background-color: #0808303f;
            backdrop-filter: blur(8px);
            border: 1px solid rgba(99, 99, 99, 0.37);
            border-radius: 10px;
            padding: 2rem;
            width: 90%;
            text-align: left;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.12);
            margin: clamp(1.5rem, 4vh, 2.5rem);
        }

        .ban-container h1 {
            margin-top: 0;
            color: #15152e;
            text-align: center;
            margin-bottom: 2rem;
        }

        .ban-container h2 {
            color: #333333;
            font-size: 1rem;
            margin-top: 1.2rem;
            margin-bottom: 0.5rem;
        }

        .ban-container p {
            color: #333333;
            font-size: 0.95rem;
            line-height: 1.6;
            margin: 0.5rem 0;
        }

        .conditions-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .conditions-list li {
            color: #333333;
            font-size: 0.95rem;
            line-height: 1.8;
            margin-bottom: 1rem;
            padding-left: 2rem;
            position: relative;
        }

        .conditions-list li:before {
            content: "●";
            color: #0819b6d0;
            font-size: 1.2rem;
            position: absolute;
            left: 0;
            top: 0;
        }

        .back-btn {
            margin-top: 2rem;
            text-align: center;
        }

        .back-btn a {
            background-color: #d8d8d888;
            color: #333333;
            font-family: 'HovesDemiBold';
            backdrop-filter: blur(5px);
            border: 1px solid rgba(99, 99, 99, 0.37);
            border-radius: 5px;
            padding: 0.8rem 2rem;
            font-size: 1rem;
            cursor: pointer;
            transition: 3s;
            text-decoration: none;
            display: inline-block;
        }

        .back-btn a:hover {
            background-color: #80808088;
        }

        @media (max-width: 750px) {
            .ban-container {
                margin: 1rem;
                padding: 1.5rem;
                max-width: 90%;
            }

            .ban-container h1 {
                font-size: 1.5rem;
            }

            .conditions-list li {
                font-size: 0.9rem;
                padding-left: 1.8rem;
            }

            .back-btn a {
                font-size: 0.9rem;
                padding: 0.6rem 1.5rem;
            }
        }
    </style>
</head>

<body>
    <div class="ban-container">
        <h1>Condiciones de uso de Leeya</h1>
        
        <p>Bienvenido a Leeya. Para mantener una comunidad segura y respetuosa, es fundamental que todos nuestros usuarios respeten las siguientes condiciones de uso. Las actividades prohibidas incluyen:</p>

        <ul class="conditions-list">
            <li><strong>Estafa o fraude:</strong> No se permite ningún tipo de engaño, falsificación de identidad o transacciones fraudulentas en la plataforma.</li>
            
            <li><strong>Producto falso o réplica:</strong> La venta de productos falsificados está estrictamente prohibida.</li>
            
            <li><strong>Suplantación de identidad:</strong> No se permite pretender ser otra persona ni crear perfiles falsos para engañar a otros usuarios.</li>
            
            <li><strong>Información o fotos falsas:</strong> Todo contenido publicado debe ser veraz y auténtico. Las descripciones engañosas o imágenes falsas están prohibidas.</li>
            
            <li><strong>Lenguaje ofensivo o acoso:</strong> No se toleran insultos, acoso, amenazas o cualquier forma de comportamiento abusivo hacia otros usuarios.</li>
            
            <li><strong>Venta de productos prohibidos:</strong> La comercialización de artículos ilegales, drogas, armas o cualquier producto prohibido por la ley está completamente prohibida.</li>
            
            <li><strong>Contenido inapropiado o ilegal:</strong> No se permite publicar contenido sexual, violento, discriminatorio o cualquier material que viole las leyes aplicables.</li>
            
            <li><strong>Solicitud de datos personales:</strong> Los usuarios no deben solicitar información personal confidencial como contraseñas, números de tarjeta de crédito o datos bancarios.</li>
            
            <li><strong>Phishing o enlaces maliciosos:</strong> Está prohibido compartir enlaces sospechosos, malware o cualquier contenido que intente comprometer la seguridad de otros usuarios.</li>
            
            <li><strong>Incumplimiento en la entrega:</strong> Los vendedores deben cumplir con sus obligaciones de entrega en los plazos acordados. El incumplimiento repetido resultará en sanciones.</li>
        </ul>

        <p style="margin-top: 2rem; font-size: 0.9rem; color: #555555;"><strong>Nota:</strong> El incumplimiento de estas condiciones puede resultar en la suspensión o eliminación permanente de tu cuenta. Gracias por ser parte de nuestra comunidad responsable.</p>

        <div class="back-btn">
            <a href="index.php">VOLVER AL INICIO</a>
        </div>
    </div>
</body>

</html>
