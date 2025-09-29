<?php
require 'auth.php';
if ($_SESSION['user_type'] !== 'host') {
    header("Location: login.php");
    exit;
}

$host_id = $_SESSION['user_id'];

require 'db.php';

$stmt = $conn->prepare("
    SELECT b.booking_id, b.start_date, b.end_date, b.total_price, u.name AS guest_name, l.title AS listing_title
    FROM bookings b
    JOIN listings l ON b.listing_id = l.listing_id
    JOIN users u ON b.guest_id = u.user_id
    WHERE l.host_id = ?
    ORDER BY b.start_date DESC
");
$stmt->bind_param("i", $host_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="ro">
<head>
  <meta charset="UTF-8" />
  <title>Rezervările mele - Host</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      background: linear-gradient(135deg, #6f42c1 0%, #0d6efd 100%);
      color: white;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      min-height: 100vh;
      margin: 0;
      padding: 2rem;
    }
    .container {
      background-color: rgba(255, 255, 255, 0.1);
      border-radius: 20px;
      padding: 2rem;
      max-width: 900px;
      margin: 0 auto;
      box-shadow: 0 4px 15px rgba(0,0,0,0.3);
    }
    h1 {
      text-align: center;
      margin-bottom: 2rem;
      font-weight: 700;
      text-shadow: 1px 1px 4px rgba(0,0,0,0.4);
    }
    table {
      width: 100%;
      border-collapse: collapse;
      color: white;
    }
    th, td {
      padding: 12px 15px;
      border-bottom: 1px solid rgba(255,255,255,0.2);
      text-align: left;
    }
    th {
      background-color: rgba(111, 66, 193, 0.8);
    }
    tr:hover {
      background-color: rgba(111, 66, 193, 0.4);
    }
    .back-btn {
      display: inline-block;
      margin-top: 1.5rem;
      background-color: #6f42c1;
      color: white;
      padding: 0.6rem 1.5rem;
      border-radius: 50px;
      text-decoration: none;
      font-weight: 600;
      box-shadow: 0 4px 10px rgba(111, 66, 193, 0.6);
      transition: background-color 0.3s ease;
    }
    .back-btn:hover {
      background-color: #563d7c;
      box-shadow: 0 6px 15px rgba(86, 61, 124, 0.8);
      color: white;
      text-decoration: none;
    }
  </style>
</head>
<body>

  <div class="container">
    <h1>Rezervările mele</h1>

    <?php if ($result->num_rows > 0): ?>
      <table>
        <thead>
          <tr>
            <th>ID Rezervare</th>
            <th>Anunț</th>
            <th>Guest</th>
            <th>Data început</th>
            <th>Data sfârșit</th>
            <th>Preț total (RON)</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($row['booking_id']) ?></td>
              <td><?= htmlspecialchars($row['listing_title']) ?></td>
              <td><?= htmlspecialchars($row['guest_name']) ?></td>
              <td><?= htmlspecialchars($row['start_date']) ?></td>
              <td><?= htmlspecialchars($row['end_date']) ?></td>
              <td><?= number_format($row['total_price'], 2) ?></td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p>Nu există rezervări pentru anunțurile tale.</p>
    <?php endif; ?>

    <a href="host_dashboard.php" class="back-btn">Înapoi la Dashboard</a>
  </div>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
