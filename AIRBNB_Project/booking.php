<?php
session_start();
require 'db.php';

// Inițializăm variabilele pentru siguranță
$error = '';
$success = '';
$listing = null;

// Verificăm dacă userul este guest autentificat
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'guest') {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Verificăm dacă există listing_id în URL
if (!isset($_GET['listing_id'])) {
    die("ID-ul anunțului nu a fost specificat.");
}

$listing_id = intval($_GET['listing_id']);

// Preluăm detalii despre listing
$stmt = $conn->prepare("SELECT title, description, price_per_night, location FROM listings WHERE listing_id = ?");
$stmt->bind_param("i", $listing_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Anunțul nu există.");
}

$listing = $result->fetch_assoc();

// Procesăm formularul de rezervare
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $start_date = $_POST['start_date'] ?? '';
    $end_date = $_POST['end_date'] ?? '';

    // Validări simple date
    if (!$start_date || !$end_date) {
        $error = "Completează ambele date.";
    } elseif ($start_date >= $end_date) {
        $error = "Data de început trebuie să fie înaintea datei de sfârșit.";
    } else {
        // Calculăm numărul de nopți
        $diff = strtotime($end_date) - strtotime($start_date);
        $nights = $diff / (60 * 60 * 24);

        // Calculăm prețul total
        $total_price = $nights * $listing['price_per_night'];

        // Găsim booking_id maxim existent
        $res = $conn->query("SELECT MAX(booking_id) AS max_id FROM bookings");
        $row = $res->fetch_assoc();
        $next_booking_id = $row['max_id'] + 1;

        // Inserăm rezervarea în baza de date cu booking_id manual
        $stmt2 = $conn->prepare("INSERT INTO bookings (booking_id, listing_id, guest_id, start_date, end_date, total_price) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt2->bind_param("iiissd", $next_booking_id, $listing_id, $user_id, $start_date, $end_date, $total_price);

        if ($stmt2->execute()) {
            $success = "Rezervarea a fost creată cu succes! Total: " . number_format($total_price, 2) . " RON";
        } else {
            $error = "Eroare la crearea rezervării. Te rugăm încearcă din nou.";
        }

        $stmt2->close();
    }
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8" />
    <title>Rezervare Anunț</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background: linear-gradient(to right, #6a11cb, #2575fc);
            color: white;
            min-height: 100vh;
            padding: 40px;
            font-family: Arial, sans-serif;
        }
        .booking-card {
            background-color: rgba(102, 51, 153, 0.85);
            border-radius: 10px;
            padding: 25px;
            max-width: 600px;
            margin: auto;
            box-shadow: 0 0 15px rgba(38, 10, 95, 0.8);
        }
        h2 {
            color: #e1c4ff;
            margin-bottom: 25px;
        }
        label {
            font-weight: 600;
        }
        .btn-submit {
            background-color: #6f42c1;
            border: none;
            color: white;
            padding: 10px 22px;
            border-radius: 6px;
            font-weight: 700;
            margin-top: 15px;
            width: 100%;
            transition: background-color 0.3s ease;
        }
        .btn-submit:hover {
            background-color: #582ea8;
            color: white;
        }
        .message-success {
            background-color: #28a745cc;
            padding: 10px 15px;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        .message-error {
            background-color: #dc3545cc;
            padding: 10px 15px;
            border-radius: 5px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="booking-card">
        <h2>Rezervare: <?= htmlspecialchars($listing['title']) ?></h2>
        <p><?= nl2br(htmlspecialchars($listing['description'])) ?></p>
        <p><strong>Preț/noapte:</strong> <?= htmlspecialchars($listing['price_per_night']) ?> RON</p>
        <p><strong>Locație:</strong> <?= htmlspecialchars($listing['location']) ?></p>

        <?php if ($error): ?>
            <div class="message-error"><?= $error ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="message-success"><?= $success ?></div>
            <a href="guest_listings.php" class="btn btn-light mt-3">Înapoi la listă</a>
        <?php else: ?>
        <form method="POST">
            <div class="mb-3">
                <label for="start_date">Data început:</label>
                <input type="date" class="form-control" id="start_date" name="start_date" required />
            </div>
            <div class="mb-3">
                <label for="end_date">Data sfârșit:</label>
                <input type="date" class="form-control" id="end_date" name="end_date" required />
            </div>
            <button type="submit" class="btn-submit">Rezervă acum</button>
        </form>
        <?php endif; ?>
    </div>
</body>
</html>
