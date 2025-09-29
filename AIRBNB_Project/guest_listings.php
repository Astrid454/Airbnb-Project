<?php
session_start();
require 'db.php';

// Verificăm dacă userul este guest autentificat
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'guest') {
    header("Location: login.php");
    exit;
}

// Preluăm listările din baza de date
$sql = "SELECT listing_id, title, description, price_per_night, location FROM listings";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8" />
    <title>Listări Anunțuri</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background: linear-gradient(to right, #6a11cb, #2575fc);
            color: white;
            min-height: 100vh;
            padding: 40px;
            font-family: Arial, sans-serif;
        }
        .listing-card {
            background-color: rgba(102, 51, 153, 0.8);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 0 10px rgba(38, 10, 95, 0.7);
        }
        h2 {
            color: #e1c4ff;
            margin-bottom: 30px;
        }
        .btn-book {
            background-color: #6f42c1;
            color: white;
            border: none;
            padding: 10px 22px;
            border-radius: 6px;
            font-weight: 700;
            transition: background-color 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }
        .btn-book:hover {
            background-color: #582ea8;
            color: white;
        }
    </style>
</head>
<body>
    <h2>Listări Anunțuri</h2>

    <?php
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo '<div class="listing-card">';
            echo '<h3>' . htmlspecialchars($row["title"]) . '</h3>';
            echo '<p>' . nl2br(htmlspecialchars($row["description"])) . '</p>';
            echo '<p><strong>Preț/noapte:</strong> ' . htmlspecialchars($row["price_per_night"]) . ' RON</p>';
            echo '<p><strong>Locație:</strong> ' . htmlspecialchars($row["location"]) . '</p>';
            echo '<a href="booking.php?listing_id=' . intval($row["listing_id"]) . '" class="btn-book">Book</a>';
            echo '</div>';
        }
    } else {
        echo '<p>Nu există anunțuri disponibile.</p>';
    }
    $conn->close();
    ?>
</body>
</html>
