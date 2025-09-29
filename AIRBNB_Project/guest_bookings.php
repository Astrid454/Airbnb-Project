<?php
session_start();
require 'db.php';

// Verificăm dacă userul este guest autentificat
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'guest') {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Preluăm rezervările guest-ului, împreună cu detalii despre listing
$sql = "
    SELECT b.booking_id, b.start_date, b.end_date, b.total_price,
           l.title, l.location, l.price_per_night
    FROM bookings b
    JOIN listings l ON b.listing_id = l.listing_id
    WHERE b.guest_id = ?
    ORDER BY b.start_date DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8" />
    <title>Rezervările mele</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background: linear-gradient(to right, #6a11cb, #2575fc);
            color: white;
            min-height: 100vh;
            padding: 40px;
            font-family: Arial, sans-serif;
        }
        h2 {
            color: #e1c4ff;
            margin-bottom: 30px;
            text-align: center;
        }
        table {
            background-color: rgba(102, 51, 153, 0.85);
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(38, 10, 95, 0.8);
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
        }
        th {
            background-color: #582ea8;
        }
        tr:nth-child(even) {
            background-color: rgba(90, 30, 140, 0.6);
        }
        .btn-back {
            margin-top: 20px;
            display: block;
            width: 200px;
            margin-left: auto;
            margin-right: auto;
            background-color: #6f42c1;
            border: none;
            color: white;
            padding: 10px 15px;
            border-radius: 6px;
            font-weight: 700;
            text-align: center;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }
        .btn-back:hover {
            background-color: #582ea8;
            color: white;
        }
    </style>
</head>
<body>
    <h2>Rezervările mele</h2>

    <?php if ($result->num_rows > 0): ?>
        <table class="table table-striped table-hover text-white">
            <thead>
                <tr>
                    <th>Anunț</th>
                    <th>Locație</th>
                    <th>Perioadă</th>
                    <th>Preț/noapte</th>
                    <th>Preț total</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['title']) ?></td>
                        <td><?= htmlspecialchars($row['location']) ?></td>
                        <td><?= htmlspecialchars($row['start_date']) ?> - <?= htmlspecialchars($row['end_date']) ?></td>
                        <td><?= number_format($row['price_per_night'], 2) ?> RON</td>
                        <td><?= number_format($row['total_price'], 2) ?> RON</td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Nu ai rezervări efectuate.</p>
    <?php endif; ?>

    <a href="guest_listings.php" class="btn-back">Înapoi la listă</a>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
