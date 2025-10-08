<?php

require_once 'database.php';

// Función para registrar un nuevo usuario
function signUp($name, $email, $password, $location)
{

    try {
        $pdo = getDBConnection();

        // Verificar si el email ya existe
        $stmt = $pdo->prepare("SELECT id FROM user WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->rowCount() > 0) {
            return ['success' => false, 'message' => 'Este email ya esta registrado'];
        }

        // Encriptar la contraseña
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insertar el nuevo usuario
        $stmt = $pdo->prepare("INSERT INTO user (name, email, passwd, location) VALUES (?, ?, ?, ?)");
        $result = $stmt->execute([$name, $email, $hashedPassword, $location]);

        if ($result) {
            return ['success' => true, 'message' => 'Usuario registrado exitosamente, ahora puedes iniciar sesion'];
        } else {
            return ['success' => false, 'message' => 'Error al registrar el usuario'];
        }

    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Error de base de datos: ' . $e->getMessage()];
    }
}

// Inicio de sesión de usuario
function loginUser($email, $password)
{
    try {
        $pdo = getDBConnection();

        $stmt = $pdo->prepare("
            SELECT id, name, email, passwd, signdate, location, lildescription, userrole
            FROM user
            WHERE email = ?
        ");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['passwd'])) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_signdate'] = $user['signdate'];
            $_SESSION['user_location'] = $user['location'];
            $_SESSION['user_description'] = $user['lildescription'];
            $_SESSION['user_role'] = $user['userrole'];

            return [
                'success' => true,
                'message' => 'Inicio de sesión exitoso'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Credenciales incorrectas.'
            ];
        }
    } catch (PDOException $e) {
        error_log("Error de base de datos en loginUser: " . $e->getMessage());
        return [
            'success' => false,
            'message' => 'Error interno al intentar iniciar sesión.'
        ];
    }
}

// Función para verificar si el usuario esta logueado
function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

// Función para validar la sesión del usuario y eliminar si no existe la cuenta por X razón
function refreshSessionUser()
{
    if (!isset($_SESSION['user_id'])) {
        return;
    }

    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT * FROM user WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();

    if ($user) {
        $_SESSION['user'] = $user;
    } else {
        session_unset();
        session_destroy();
    }
}

function userExists($email)
{
    $pdo = getDBConnection();

    $stmt = $pdo->prepare("SELECT id FROM user WHERE email = :email LIMIT 1");
    $stmt->execute(['email' => $email]);
    return $stmt->fetch() !== false;
}

// Función para cerrar sesion
function logoutUser()
{
    session_unset();
    session_destroy();
}

function changeUserPassword($user_id, $new_password)
{
    try {
        $pdo = getDBConnection();
        $hashed = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE user SET passwd = ? WHERE id = ?");
        $result = $stmt->execute([$hashed, $user_id]);
        if ($result) {
            return ['success' => true, 'message' => 'Contraseña actualizada correctamente.'];
        } else {
            return ['success' => false, 'message' => 'No se pudo actualizar la contraseña.'];
        }
    } catch (PDOException $e) {
        error_log("Error al cambiar contraseña: " . $e->getMessage());
        return ['success' => false, 'message' => 'Error de base de datos al cambiar contraseña.'];
    }
}

function changeUserDescription($user_id, $new_description)
{
    try {
        $pdo = getDBConnection();
        $stmt = $pdo->prepare("UPDATE user SET lildescription = ? WHERE id = ?");
        $result = $stmt->execute([$new_description, $user_id]);
        if ($result) {
            return ['success' => true, 'message' => 'Descripcion actualizada correctamente.'];
        } else {
            return ['success' => false, 'message' => 'No se pudo actualizar la descripcion.'];
        }
    } catch (PDOException $e) {
        error_log("Error al cambiar la descripcion: " . $e->getMessage());
        return ['success' => false, 'message' => 'Error de base de datos al cambiar la descripcion.'];
    }
}

function changeUserLocation($user_id, $new_location)
{
    try {
        $pdo = getDBConnection();
        $stmt = $pdo->prepare("UPDATE user SET location = ? WHERE id = ?");
        $result = $stmt->execute([$new_location, $user_id]);
        if ($result) {
            return ['success' => true, 'message' => 'Ubicacion actualizada correctamente.'];
        } else {
            return ['success' => false, 'message' => 'No se pudo actualizar la ubicacion.'];
        }
    } catch (PDOException $e) {
        error_log("Error al cambiar la ubicacion: " . $e->getMessage());
        return ['success' => false, 'message' => 'Error de base de datos al cambiar la ubicacion.'];
    }
}

// Crear un libro (publicar)
function createBook($ownerid, $name, $author, $genre, $editorial, $description, $qstatus, $bookpic, $typeof, $status, $price = null, $limdate = null)
{
    try {
        $pdo = getDBConnection();
        $fields = "ownerid, name, author, genre, editorial, description, qstatus, bookpic, typeof, status";
        $values = "?, ?, ?, ?, ?, ?, ?, ?, ?, ?";
        $params = [$ownerid, $name, $author, $genre, $editorial, $description, $qstatus, $bookpic, $typeof, $status];

        if ($price !== null) {
            $fields .= ", price";
            $values .= ", ?";
            $params[] = $price;
        }
        if ($typeof === "Subasta" && $limdate !== null) {
            $fields .= ", limdate";
            $values .= ", ?";
            $params[] = $limdate;
        }

        $stmt = $pdo->prepare("INSERT INTO book ($fields) VALUES ($values)");
        $result = $stmt->execute($params);

        if ($result) {
            return ['success' => true, 'message' => 'Libro publicado exitosamente.'];
        } else {
            return ['success' => false, 'message' => 'Error al publicar el libro.'];
        }
    } catch (PDOException $e) {
        error_log("Error al publicar el libro: " . $e->getMessage());
        return ['success' => false, 'message' => 'Error de base de datos al publicar el libro: ' . $e->getMessage()];
    }
}

// Obtener los libros del usuario x su id
function getBooksByUserId($user_id)
{
    try {
        $pdo = getDBConnection();
        $stmt = $pdo->prepare("
            SELECT id, name, author, genre, editorial, description, qstatus, bookpic, typeof, status, price
            FROM book
            WHERE ownerid = ? AND status = 1
            ORDER BY id DESC
        ");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error al obtener libros del usuario: " . $e->getMessage());
        return [];
    }
}

// Obtiene el libro segun el id del mismo
function getBookById($book_id)
{
    try {
        $pdo = getDBConnection();
        $stmt = $pdo->prepare("SELECT * FROM book WHERE id = ?");
        $stmt->execute([$book_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error al obtener libro por ID: " . $e->getMessage());
        return null;
    }
}

// Obtener los 4 libros + recientes xra el index
function getLatestBooks($limit = 4, $exclude_user_id = null)
{
    try {
        $pdo = getDBConnection();
        $query = "SELECT * FROM book WHERE status = 1";
        if ($exclude_user_id) {
            $query .= " AND ownerid != ?";
            $query .= " ORDER BY id DESC LIMIT ?";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$exclude_user_id, $limit]);
        } else {
            $query .= " ORDER BY id DESC LIMIT ?";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$limit]);
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error al obtener últimos libros: " . $e->getMessage());
        return [];
    }
}

// Obtener los libros para explore
function searchBooks($search = '', $type = '', $exclude_user_id = null)
{
    try {
        $pdo = getDBConnection();
        $params = [];
        $query = "SELECT b.*, u.name AS owner_name FROM book b JOIN user u ON b.ownerid = u.id WHERE b.status = 1";

        if ($exclude_user_id) {
            $query .= " AND b.ownerid != ?";
            $params[] = $exclude_user_id;
        }

        if ($type !== '') {
            $query .= " AND b.typeof = ?";
            $params[] = $type;
        }

        if ($search !== '') {
            $query .= " AND (b.name LIKE ? OR b.author LIKE ? OR b.genre LIKE ?)";
            $searchParam = '%' . $search . '%';
            $params[] = $searchParam;
            $params[] = $searchParam;
            $params[] = $searchParam;
        }

        $query .= " ORDER BY b.id DESC";
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error en searchBooks: " . $e->getMessage());
        return [];
    }
}

// Buscar usuarios por nombre
function searchUsers($search = '', $exclude_user_id = null)
{
    try {
        $pdo = getDBConnection();
        $params = [];
        $query = "SELECT id, name, email, location, lildescription FROM user WHERE 1=1";

        if ($exclude_user_id) {
            $query .= " AND id != ?";
            $params[] = $exclude_user_id;
        }

        if ($search !== '') {
            $query .= " AND name LIKE ?";
            $params[] = '%' . $search . '%'; // Manejado x similitud de cadena
        }

        $query .= " ORDER BY name ASC";
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error en searchUsers: " . $e->getMessage());
        return [];
    }
}

// Funcion dedicada a cambiar el estado de libros de subasta con fecha excedida
function updateExpiredAuctions()
{
    try {
        $pdo = getDBConnection();
        $today = date('Y-m-d');
        $stmt = $pdo->prepare("
            UPDATE book
            SET status = 0
            WHERE typeof = 'Subasta'
              AND limdate IS NOT NULL
              AND limdate < ?
              AND status = 1
        ");
        $stmt->execute([$today]);
    } catch (PDOException $e) {
        error_log("Error al actualizar subastas expiradas: " . $e->getMessage());
    }
}

// Crear propuestas
function createProposal($interested, $targetbookid, $type, $money = null)
{
    try {
        $pdo = getDBConnection();
        $stmt = $pdo->prepare("INSERT INTO proposal (interested, targetbookid, money, status, proposaldate) VALUES (?, ?, ?, 'En proceso', CURDATE())");
        $stmt->execute([$interested, $targetbookid, $money]);
        return $pdo->lastInsertId();
    } catch (PDOException $e) {
        error_log("Error al crear propuesta: " . $e->getMessage());
        return false;
    }
}

// Crear propuesta de intercambio (varios libros ofrecidos)
function createExchangeProposal($interested, $targetbookid, $offered_books)
{
    $proposal_id = createProposal($interested, $targetbookid, 'Intercambio');
    if ($proposal_id && is_array($offered_books)) {
        try {
            $pdo = getDBConnection();
            foreach ($offered_books as $offered_book_id) {
                $stmt = $pdo->prepare("INSERT INTO proposal_book (bookid, proposalid) VALUES (?, ?)");
                $stmt->execute([$offered_book_id, $proposal_id]);
            }
            return $proposal_id;
        } catch (PDOException $e) {
            error_log("Error al crear propuesta de intercambio: " . $e->getMessage());
            return false;
        }
    }
    return false;
}

// Crear propuesta de subasta y actualizar monto en book
function createAuctionProposal($interested, $targetbookid, $amount)
{
    $proposal_id = createProposal($interested, $targetbookid, 'Subasta', $amount);
    if ($proposal_id) {
        try {
            $pdo = getDBConnection();
            $stmt = $pdo->prepare("UPDATE book SET price = ? WHERE id = ?");
            $stmt->execute([$amount, $targetbookid]);
            return $proposal_id;
        } catch (PDOException $e) {
            error_log("Error al actualizar monto de subasta: " . $e->getMessage());
            return false;
        }
    }
    return false;
}

// Edición del libro por usuario dueño
function updateBook($book_id, $data)
{
    try {
        $pdo = getDBConnection();
        $fields = [];
        $params = [];

        foreach ($data as $key => $value) {
            $fields[] = "$key = ?";
            $params[] = $value;
        }
        $params[] = $book_id;

        $sql = "UPDATE book SET " . implode(', ', $fields) . " WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute($params);
    } catch (PDOException $e) {
        error_log("Error al actualizar libro: " . $e->getMessage());
        return false;
    }
}

// Eliminar (cambiar el estado del libro)
function deleteBook($book_id)
{
    try {
        $pdo = getDBConnection();
        $stmt = $pdo->prepare("UPDATE book SET status = 0 WHERE id = ?");
        return $stmt->execute([$book_id]);
    } catch (PDOException $e) {
        error_log("Error al eliminar libro: " . $e->getMessage());
        return false;
    }
}

// Propuestas hechas
function getSentProposals($user_id)
{
    try {
        $pdo = getDBConnection();
        $stmt = $pdo->prepare("
            SELECT 
                p.*, 
                b.name AS book_name, 
                b.author, 
                b.bookpic, 
                b.typeof, 
                b.price, 
                b.ownerid, 
                u.name AS owner_name
            FROM proposal p
            JOIN book b ON p.targetbookid = b.id
            JOIN user u ON b.ownerid = u.id
            WHERE 
                p.interested = ?
                AND (
                    b.status = 1
                    OR (b.status = 0 AND p.status = 'Finalizada')
                )
                AND (p.status = 'En proceso' OR p.status = 'Finalizada' OR p.status = 'Rechazada')
            ORDER BY p.id DESC
        ");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error al obtener propuestas enviadas: " . $e->getMessage());
        return [];
    }
}

// Propuestas recibidas
function getReceivedProposals($user_id)
{
    try {
        $pdo = getDBConnection();
        $stmt = $pdo->prepare("
            SELECT 
                p.*, 
                b.name AS book_name, 
                b.author, 
                b.bookpic, 
                b.typeof, 
                b.price, 
                u.name AS interested_name, 
                u.id AS interested_id
            FROM proposal p
            JOIN book b ON p.targetbookid = b.id
            JOIN user u ON p.interested = u.id
            WHERE 
                b.ownerid = ?
                AND (
                    b.status = 1
                    OR (b.status = 0 AND p.status = 'Finalizada')
                )
                AND (p.status = 'En proceso' OR p.status = 'Finalizada' OR p.status = 'Rechazada')
            ORDER BY p.id DESC
        ");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error al obtener propuestas recibidas: " . $e->getMessage());
        return [];
    }
}

// Cambiar estado de propuesta
function updateProposalStatus($proposal_id, $new_status)
{
    try {
        $pdo = getDBConnection();
        $stmt = $pdo->prepare("UPDATE proposal SET status = ? WHERE id = ?");
        return $stmt->execute([$new_status, $proposal_id]);
    } catch (PDOException $e) {
        error_log("Error al actualizar estado de propuesta: " . $e->getMessage());
        return false;
    }
}

// Finalizar propuesta y deshabilitar libro
function finalizeProposal($proposal_id)
{
    try {
        $pdo = getDBConnection();
        // Cambia estado de propuesta
        $stmt = $pdo->prepare("UPDATE proposal SET status = 'Finalizada' WHERE id = ?");
        $stmt->execute([$proposal_id]);
        // Marca libro como no disponible
        $stmt2 = $pdo->prepare("UPDATE book SET status = 0 WHERE id = (SELECT targetbookid FROM proposal WHERE id = ?)");
        $stmt2->execute([$proposal_id]);
        return true;
    } catch (PDOException $e) {
        error_log("Error al finalizar propuesta: " . $e->getMessage());
        return false;
    }
}

// Obtener el número de propuestas para mostrar en notificaciones
function getPendingProposalsCount($user_id)
{
    try {
        $pdo = getDBConnection();

        // Enviadas
        $stmt1 = $pdo->prepare("
            SELECT COUNT(*) 
            FROM proposal p
            JOIN book b ON p.targetbookid = b.id
            WHERE 
                p.interested = ? 
                AND p.status = 'En proceso'
                AND b.status = 1
        ");
        $stmt1->execute([$user_id]);
        $sent = $stmt1->fetchColumn();

        // Recibidas
        $stmt2 = $pdo->prepare("
            SELECT COUNT(*) 
            FROM proposal p
            JOIN book b ON p.targetbookid = b.id
            WHERE 
                b.ownerid = ? 
                AND b.status = 1
                AND (p.status = 'En proceso' OR p.status = 'Finalizada')
        ");
        $stmt2->execute([$user_id]);
        $received = $stmt2->fetchColumn();

        return [
            'sent' => (int) $sent,
            'received' => (int) $received
        ];
    } catch (PDOException $e) {
        error_log("Error al contar propuestas pendientes: " . $e->getMessage());
        return ['sent' => 0, 'received' => 0];
    }
}

// Obtener datos de usuarios por id

function getUserById($user_id)
{
    try {
        $pdo = getDBConnection();
        $stmt = $pdo->prepare("SELECT id, name, email, location, lildescription, signdate FROM user WHERE id = ?");
        $stmt->execute([$user_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error al obtener usuario por ID: " . $e->getMessage());
        return null;
    }
}

// Obtener reseñas de usuario segun id
function getUserRates($user_id)
{
    try {
        $pdo = getDBConnection();
        $stmt = $pdo->prepare("
            SELECT r.*, u.name AS sender_name
            FROM rate r
            JOIN user u ON r.rater = u.id
            WHERE r.ratee = ?
            ORDER BY r.id DESC
        ");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error al obtener reseñas: " . $e->getMessage());
        return [];
    }
}

// Registrar una reseña
function createUserRate($sender_id, $target_id, $stars, $description)
{
    try {
        $pdo = getDBConnection();
        $stmt = $pdo->prepare("INSERT INTO rate (rater, ratee, rating, commentary, ratedate) VALUES (?, ?, ?, ?, NOW())");
        return $stmt->execute([$sender_id, $target_id, $stars, $description]);
    } catch (PDOException $e) {
        error_log("Error al crear reseña: " . $e->getMessage());
        return false;
    }
}

// Registrar un reporte
function createUserReport($sender_id, $target_id, $motive, $description)
{
    try {
        $pdo = getDBConnection();
        $stmt = $pdo->prepare("INSERT INTO reports (idreporter, idreported, motive, description, datereport, ischecked) VALUES (?, ?, ?, ?, NOW(), 0)");
        return $stmt->execute([$sender_id, $target_id, $motive, $description]);
    } catch (PDOException $e) {
        error_log("Error al crear reporte: " . $e->getMessage());
        return false;
    }
}

function getExchangeBooks($proposal_id)
{
    try {
        $pdo = getDBConnection();
        $stmt = $pdo->prepare("
            SELECT b.id, b.name, b.author, b.bookpic
            FROM proposal_book pb
            JOIN book b ON pb.bookid = b.id
            WHERE pb.proposalid = ?
        ");
        $stmt->execute([$proposal_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error al obtener libros ofrecidos en intercambio: " . $e->getMessage());
        return [];
    }
}