<?php
require 'auth.php';
require 'db.php';

if ($_SESSION['user_type'] !== 'host') {
    header("Location: login.php");
    exit;
}

$host_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Review-uri primite</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f5f0fa;
      font-family: 'Segoe UI', sans-serif;
    }
    .container {
      margin-top: 40px;
      max-width: 900px;
    }
    .card {
      border-radius: 15px;
      box-shadow: 0 4px 10px rgba(111, 66, 193, 0.2);
      margin-bottom: 20px;
    }
    .card-title {
      color: #6f42c1;
      font-weight: 600;
    }
    .btn-back {
      background-color: #6f42c1;
      color: white;
      font-weight: 600;
      border-radius: 10px;
      padding: 0.5rem 1.5rem;
      text-decoration: none;
    }
    .btn-back:hover {
      background-color: #5936a2;
      color: white;
    }
  </style>
</head>
<body>

<div class="container">
  <h2 class="mb-4 text-center text-primary">Review-uri primite pentru anunțurile tale</h2>

  <?php
  $stmt = $conn->prepare("
    SELECT r.rating, r.comment, r.created_at, l.title
    FROM Reviews r
    JOIN Bookings b ON r.booking_id = b.booking_id
    JOIN Listings l ON b.listing_id = l.listing_id
    WHERE l.host_id = ?
    ORDER BY r.created_at DESC
  ");
  $stmt->bind_param("i", $host_id);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows === 0): ?>
    <div class="alert alert-info">Momentan nu ai primit review-uri.</div>
  <?php else:
    while ($row = $result->fetch_assoc()): ?>
      <div class="card p-3">
        <h5 class="card-title"><?= htmlspecialchars($row['title']) ?></h5>
        <p><strong>Rating:</strong> <?= $row['rating'] ?>/5</p>
        <p><strong>Comentariu:</strong> <?= nl2br(htmlspecialchars($row['comment'])) ?></p>
        <p class="text-muted"><small>Postat pe: <?= $row['created_at'] ?></small></p>
      </div>
    <?php endwhile;
  endif;
  ?>

  <div class="text-center mt-4">
    <a href="host_dashboard.php" class="btn-back">Înapoi la Dashboard</a>
  </div>
</div>

</body>
</html>
