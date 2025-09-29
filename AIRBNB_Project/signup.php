<?php
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Preia datele din formular în siguranță
    $name = isset($_POST['name']) ? trim($_POST['name']) : null;
    $email = isset($_POST['email']) ? trim($_POST['email']) : null;
    $password = isset($_POST['password']) ? $_POST['password'] : null;
    $user_type = isset($_POST['user_type']) ? $_POST['user_type'] : null;

    // Validări simple
    if (!$name || !$email || !$password || !$user_type) {
        die("Toate câmpurile sunt obligatorii!");
    }

    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Verifică dacă emailul există deja
    $check = $conn->prepare("SELECT * FROM Users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        die("Emailul este deja folosit!");
    }

    // Găsește următorul user_id disponibil
    $result = $conn->query("SELECT MAX(user_id) AS max_id FROM Users");
    $row = $result->fetch_assoc();
    $new_user_id = $row['max_id'] + 1;
    if ($new_user_id === null) {
        $new_user_id = 1;
    }

    // Inserează utilizatorul
    $stmt = $conn->prepare("INSERT INTO Users (user_id, name, email, password, user_type, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("issss", $new_user_id, $name, $email, $password_hash, $user_type);

    if ($stmt->execute()) {
        $_SESSION['user_id'] = $new_user_id;
        $_SESSION['user_type'] = $user_type;

        if ($user_type === 'host') {
            header("Location: host_dashboard.php");
        } else {
            header("Location: guest_dashboard.php");
        }
        exit();
    } else {
        die("Eroare la înregistrare: " . $stmt->error);
    }

} else {
    // Dacă nu e POST, poți redirecționa sau arăta un mesaj simplu
    header("Location: signup.html");
    exit();
}
