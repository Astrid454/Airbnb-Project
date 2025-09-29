<?php
require 'auth.php';
require 'db.php';

if ($_SESSION['user_type'] !== 'guest') {
    header("Location: login.php");
    exit;
}

$guest_id = $_SESSION['user_id'];
$message = "";

// Procesare trimitere review
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $booking_id = $_POST['booking_id'];
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];

    // Verificăm dacă booking-ul aparține guest-ului
    $stmt = $conn->prepare("SELECT booking_id FROM Bookings WHERE booking_id = ? AND guest_id = ?");
    $stmt->bind_param("ii", $booking_id, $guest_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        // Obținem ultimul review_id și incrementăm manual
        $resultMax = $conn->query("SELECT MAX(review_id) AS max_id FROM Reviews");
        $rowMax = $resultMax->fetch_assoc();
        $next_id = $rowMax['max_id'] ? $rowMax['max_id'] + 1 : 1;

        // Inserăm review-ul cu review_id manual
        $stmt = $conn->prepare("INSERT INTO Reviews (review_id, booking_id, rating, comment, created_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->bind_param("iiis", $next_id, $booking_id, $rating, $comment);
        if ($stmt->execute()) {
            $message = "Review adăugat cu succes!";
        } else {
            $message = "Eroare la adăugarea review-ului.";
        }
    } else {
        $message = "Rezervare invalidă.";
    }
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
  <meta charset="UTF-8" />
  <title>Adaugă review</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      background-color: #f8f0fc; /* mov foarte deschis */
      color: #4b2a65; /* mov închis pentru text */
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    h2 {
      color: #6f42c1; /* mov vibrant */
      text-align: center;
      margin-bottom: 2rem;
      font-weight: 700;
    }
    .form-label {
      color: #7b4fcc;
      font-weight: 600;
    }
    .btn-primary {
      background-color: #7b4fcc;
      border-color: #6f42c1;
      font-weight: 600;
      box-shadow: 0 4px 10px rgba(111, 66, 193, 0.5);
      transition: background-color 0.3s ease;
    }
    .btn-primary:hover {
      background-color: #5a2fa6;
      border-color: #4b2a65;
    }
    .form-select {
      border: 2px solid #a085cc;
      transition: border-color 0.3s ease;
    }
    .form-select:focus {
      border-color: #6f42c1;
      box-shadow: 0 0 5px #6f42c1;
      outline: none;
    }
    textarea.form-control {
      border: 2px solid #a085cc;
    }
    textarea.form-control:focus {
      border-color: #6f42c1;
      box-shadow: 0 0 5px #6f42c1;
      outline: none;
    }
    .alert-info {
      background-color: #e8dafc;
      color: #4b2a65;
      border: 1px solid #b39ddb;
    }
    a.btn-link {
      color: #6f42c1;
      font-weight: 600;
      text-decoration: none;
      transition: color 0.3s ease;
    }
    a.btn-link:hover {
      color: #4b2a65;
      text-decoration: underline;
    }
  </style>
</head>
<body>

<div class="container mt-5">
  <h2>Adaugă review pentru rezervările tale</h2>

  <?php if ($message): ?>
    <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
  <?php endif; ?>

  <form method="POST">
    <div class="mb-3">
      <label for="booking_id" class="form-label">Selectează rezervarea</label>
      <select class="form-select" id="booking_id" name="booking_id" required>
        <option value="" selected disabled>Alege rezervarea</option>
        <?php
        // Preluăm toate rezervările guest-ului, indiferent dacă au review sau nu
        $stmt = $conn->prepare("
          SELECT b.booking_id, l.title, b.start_date, b.end_date 
          FROM Bookings b
          JOIN Listings l ON b.listing_id = l.listing_id
          WHERE b.guest_id = ?
          ORDER BY b.start_date DESC
        ");
        $stmt->bind_param("i", $guest_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            echo "<option disabled>Nu ai rezervări.</option>";
        } else {
            while ($row = $result->fetch_assoc()) {
                $booking_label = htmlspecialchars($row['title']) . " | " . $row['start_date'] . " - " . $row['end_date'];
                echo "<option value='{$row['booking_id']}'>{$booking_label}</option>";
            }
        }
        ?>
      </select>
    </div>

    <div class="mb-3">
      <label for="rating" class="form-label">Rating</label>
      <select class="form-select" id="rating" name="rating" required>
        <option value="" selected disabled>Alege ratingul</option>
        <option value="5">5 - Excelent</option>
        <option value="4">4 - Foarte bine</option>
        <option value="3">3 - Bine</option>
        <option value="2">2 - Slab</option>
        <option value="1">1 - Foarte slab</option>
      </select>
    </div>

    <div class="mb-3">
      <label for="comment" class="form-label">Comentariu</label>
      <textarea class="form-control" id="comment" name="comment" rows="4" placeholder="Scrie aici review-ul tău..." required></textarea>
    </div>

    <button type="submit" class="btn btn-primary">Trimite review</button>
  </form>

  <a href="guest_dashboard.php" class="btn btn-link mt-3">Înapoi la Dashboard</a>
</div>

</body>
</html>
