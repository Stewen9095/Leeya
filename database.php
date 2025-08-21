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
USE Leeya;

CREATE TABLE user (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    passwd VARCHAR(255) NOT NULL,
    signdate DATE DEFAULT CURRENT_TIMESTAMP,
    location VARCHAR(255) NOT NULL,
    confirmation BOOLEAN DEFAULT TRUE,
    userrole VARCHAR(100) DEFAULT 'user'
);

CREATE TABLE book (
    id INT PRIMARY KEY AUTO_INCREMENT,
    ownerid INT,
    name VARCHAR(255),
    author VARCHAR(255),
    genre VARCHAR(100),
    available VARCHAR(50),
    price NUMERIC(10, 2),
    editorial VARCHAR(255),
    description TEXT,
    qstatus NUMERIC,
    FOREIGN KEY (ownerid) REFERENCES user(id)
);

CREATE TABLE proposal (
    id INT PRIMARY KEY AUTO_INCREMENT,
    interested INT,
    targetbookid INT,
    money NUMERIC(10, 2),
    status VARCHAR(50),
    proposaldate DATE,
    FOREIGN KEY (interested) REFERENCES user(id),
    FOREIGN KEY (targetbookid) REFERENCES book(id)
);

-- Libros ofrecidos en una propuesta
CREATE TABLE proposal_book (
    id INT PRIMARY KEY AUTO_INCREMENT,
    bookid INT,
    proposalid INT,
    FOREIGN KEY (bookid) REFERENCES book(id),
    FOREIGN KEY (proposalid) REFERENCES proposal(id)
);

CREATE TABLE transaction (
    id INT PRIMARY KEY AUTO_INCREMENT,
    trxstatus VARCHAR(50),
    trxdate DATE,
    proposalid INT,
    FOREIGN KEY (proposalid) REFERENCES proposal(id)
);

CREATE TABLE rate (
    id INT PRIMARY KEY AUTO_INCREMENT,
    transaction INT UNIQUE,
    rater INT,
    ratee INT,
    rating INT,
    commentary VARCHAR(500),
    ratedate DATE,
    FOREIGN KEY (transaction) REFERENCES transaction(id),
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
    FOREIGN KEY (idreporter) REFERENCES user(id),
    FOREIGN KEY (idreported) REFERENCES user(id)
);

CREATE TABLE notifications (
    id INT PRIMARY KEY AUTO_INCREMENT,
    idproposal INT,
    readed BOOLEAN,
    datenotification DATE,
    FOREIGN KEY (idproposal) REFERENCES proposal(id)
);

CREATE TABLE chat (
    id INT PRIMARY KEY AUTO_INCREMENT,
    proposalid INT,
    status VARCHAR(50),
    FOREIGN KEY (proposalid) REFERENCES proposal(id)
);

CREATE TABLE message (
    id INT PRIMARY KEY AUTO_INCREMENT,
    chatid INT,
    messengerid INT,
    content VARCHAR(1000),
    senddate DATE,
    readed BOOLEAN,
    FOREIGN KEY (chatid) REFERENCES chat(id),
    FOREIGN KEY (messengerid) REFERENCES user(id)
);


*/

?>