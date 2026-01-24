<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'leeya');

function getDBConnection()
{
    try {
        $pdo = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        );
        return $pdo;
    } catch (PDOException $e) {
        die("Error de conexión: " . $e->getMessage());
    }
}


/*

CREATE DATABASE leeya;
USE leeya;


CREATE TABLE user (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    passwd VARCHAR(255) NOT NULL,
    signdate DATE DEFAULT CURRENT_TIMESTAMP,
    location VARCHAR(255) NOT NULL,
    lildescription VARCHAR(255) DEFAULT '',
    userrole VARCHAR(100) DEFAULT 'user'
);


CREATE TABLE book (
    id INT PRIMARY KEY AUTO_INCREMENT,
    ownerid INT, -- Quien es el dueno del libro
    name VARCHAR(255), -- ☻
    author VARCHAR(255), -- ☻
    genre VARCHAR(100), -- ☻
    editorial VARCHAR(255), -- ☻
    description TEXT, -- ☻
    qstatus NUMERIC, -- De 0 a 5 estrellas como se encuentra el libro
    bookpic VARCHAR(500), -- ☻ Link de la imagen
    typeof VARCHAR(50), -- Si es una venta, donacion, intercambio o subasta
    status BOOLEAN, -- Si esta disponible o no
    price NUMERIC(10, 2), -- precio para el caso de venta o subasta
    limdate DATE, -- En caso de ser subasta se impone una fecha limite
    FOREIGN KEY (ownerid) REFERENCES user(id)
);


CREATE TABLE proposal (
    id INT PRIMARY KEY AUTO_INCREMENT,
    interested INT,
    targetbookid INT,
    money NUMERIC(10, 2),
    status VARCHAR(50), -- En proceso / Finalizada / Cancelada / Rechazada
    proposaldate DATE,
    FOREIGN KEY (interested) REFERENCES user(id),
    FOREIGN KEY (targetbookid) REFERENCES book(id)
);


-- Libros ofrecidos en una propuesta de intercambio
CREATE TABLE proposal_book (
    id INT PRIMARY KEY AUTO_INCREMENT,
    bookid INT,
    proposalid INT,
    FOREIGN KEY (bookid) REFERENCES book(id),
    FOREIGN KEY (proposalid) REFERENCES proposal(id)
);


CREATE TABLE rate (
    id INT PRIMARY KEY AUTO_INCREMENT,
    rater INT,
    ratee INT,
    rating NUMERIC, -- Calificacion de un usuario x otro usuario
    commentary VARCHAR(500),
    ratedate DATE,
    FOREIGN KEY (rater) REFERENCES user(id),
    FOREIGN KEY (ratee) REFERENCES user(id)
);

CREATE TABLE reports (
    id INT PRIMARY KEY AUTO_INCREMENT,
    idreporter INT,
    idreported INT,
    motive VARCHAR(255),
    description TEXT,
    datereport DATE,
    ischecked BOOlEAN, -- Si el administrador ya reviso dicho reporte
    FOREIGN KEY (idreporter) REFERENCES user(id),
    FOREIGN KEY (idreported) REFERENCES user(id)
);

INSERT INTO user (
    name,
    email,
    passwd,
    signdate,
    location,
    userrole
) VALUES (
    'Admin',
    'admin@admin.com',
    '$2y$10$NOeiZ/8J45lVLSe2P/jb6.ZyRIoiSksFk2qBr03a29C/T3612KzAy', -- Contraseña temporal "123456"
    CURDATE(),
    'Sistema',
    'admin'
);

*/
?>