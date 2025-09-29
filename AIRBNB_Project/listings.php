<?php
require 'auth.php';
require 'db.php';

if ($_SESSION['user_type'] !== 'host') {
    header("Location: login.php");
    exit;
}

$host_id = $_SESSION['user_id'];

// Adăugare listing
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $desc = $_POST['description'];
    $price = $_POST['price'];
    $loc = $_POST['location'];

    // Găsim listing_id maxim existent
    $resMax = $conn->query("SELECT MAX(listing_id) AS max_id FROM Listings");
    $rowMax = $resMax->fetch_assoc();
    $next_listing_id = $rowMax['max_id'] + 1;

    $stmt = $conn->prepare("INSERT INTO Listings (listing_id, host_id, title, description, price_per_night, location, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("iissds", $next_listing_id, $host_id, $title, $desc, $price, $loc);
    
    if ($stmt->execute()) {
        $success_msg = "Anunțul a fost adăugat cu succes!";
    } else {
        $error_msg = "Eroare la adăugarea anunțului. Încearcă din nou.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Listările mele</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">
  <h2>Adaugă un anunț nou</h2>

  <?php if (!empty($success_msg)): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success_msg) ?></div>
  <?php endif; ?>

  <?php if (!empty($error_msg)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error_msg) ?></div>
  <?php endif; ?>

  <form method="POST" class="mb-5">
    <input class="form-control mb-2" name="title" placeholder="Titlu" required>
    <textarea class="form-control mb-2" name="description" placeholder="Descriere" required></textarea>
    <input class="form-control mb-2" name="price" type="number" step="0.01" placeholder="Preț pe noapte" required>
    <input class="form-control mb-2" name="location" placeholder="Locație" required>
    <button class="btn btn-primary" type="submit">Salvează</button>
  </form>

  <h3>Anunțurile tale:</h3>
  <?php
    $res = $conn->query("SELECT * FROM Listings WHERE host_id = $host_id ORDER BY created_at DESC");
    while ($row = $res->fetch_assoc()) {
      echo "<div class='card mb-3 p-3'>";
      echo "<h5>" . htmlspecialchars($row['title']) . " <small class='text-muted'>" . htmlspecialchars($row['location']) . "</small></h5>";
      echo "<p>" . nl2br(htmlspecialchars($row['description'])) . "</p>";
      echo "<strong>" . number_format($row['price_per_night'], 2) . " RON / noapte</strong>";
      echo "</div>";
    }
  ?>
</div>
</body>
</html>
