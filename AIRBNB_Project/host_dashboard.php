<?php
require 'auth.php';
if ($_SESSION['user_type'] !== 'host') {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Host Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f5f0fa;
      font-family: 'Segoe UI', sans-serif;
    }
    .header {
      background-color: #6f42c1;
      color: white;
      padding: 1.5rem;
      text-align: center;
      border-radius: 0 0 20px 20px;
      position: relative;
    }
    .logout-btn {
      position: absolute;
      right: 1.5rem;
      top: 1.5rem;
      background-color: #f8f9fa;
      color: #6f42c1;
      border: none;
      padding: 0.4rem 1rem;
      border-radius: 8px;
      font-weight: 600;
      transition: background-color 0.3s ease, color 0.3s ease;
      text-decoration: none;
    }
    .logout-btn:hover {
      background-color: #5936a2;
      color: white;
    }
    .btn-purple {
      background-color: #6f42c1;
      color: white;
      font-weight: 600;
      border-radius: 50px;
      padding: 0.75rem 2rem;
      font-size: 1.1rem;
      border: none;
      display: inline-block;
      margin: 0 10px 10px 0;
      box-shadow: 0 4px 10px rgba(111, 66, 193, 0.6);
      transition: background-color 0.3s ease;
      text-decoration: none;
      cursor: pointer;
    }
    .btn-purple:hover {
      background-color: #5936a2;
      box-shadow: 0 6px 15px rgba(89, 54, 162, 0.8);
      color: white;
      text-decoration: none;
    }
    .container {
      margin-top: 2rem;
      max-width: 600px;
    }
    h3 {
      margin-bottom: 1.5rem;
      font-weight: 600;
      color: #4b3a77;
      text-align: center;
    }
  </style>
</head>
<body>

<div class="header">
  <h1>Bine ai venit, host!</h1>
  <a href="logout.php" class="logout-btn">Logout</a>
</div>

<div class="container text-center">
  <h3>Acțiuni rapide</h3>
  <a href="listings.php" class="btn btn-purple">Adaugă anunțuri</a>
  <a href="host_bookings.php" class="btn btn-purple">Vezi rezervări</a>
  <a href="host_reviews.php" class="btn btn-purple">Vezi review-uri</a> <!-- Buton nou -->
</div>

</body>
</html>
