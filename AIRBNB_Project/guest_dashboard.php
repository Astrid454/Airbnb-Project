<?php
require 'auth.php';
if ($_SESSION['user_type'] !== 'guest') {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
  <meta charset="UTF-8" />
  <title>Guest Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      background: linear-gradient(135deg, #6f42c1 0%, #0d6efd 100%);
      color: white;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      min-height: 100vh;
      margin: 0;
      display: flex;
      flex-direction: column;
      justify-content: flex-start;
      align-items: center;
      padding: 2rem 1rem;
    }
    .header {
      background-color: rgba(111, 66, 193, 0.85);
      width: 100%;
      max-width: 600px;
      border-radius: 0 0 20px 20px;
      padding: 1.5rem;
      text-align: center;
      box-shadow: 0 4px 10px rgba(0,0,0,0.25);
      position: relative;
      margin-bottom: 2rem;
    }
    .header h1 {
      margin: 0;
      font-weight: 700;
      font-size: 2rem;
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
      background-color: #6f42c1;
      color: white;
    }
    .container {
      background-color: rgba(255, 255, 255, 0.1);
      border-radius: 20px;
      padding: 2rem;
      max-width: 600px;
      width: 100%;
      box-shadow: 0 4px 15px rgba(0,0,0,0.2);
      text-align: center;
    }
    h3 {
      margin-bottom: 1.5rem;
      font-weight: 600;
      text-shadow: 1px 1px 3px rgba(0,0,0,0.3);
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
      margin: 0 10px 10px 10px;
      box-shadow: 0 4px 10px rgba(111, 66, 193, 0.6);
      transition: background-color 0.3s ease;
      text-decoration: none;
      cursor: pointer;
    }
    .btn-purple:hover {
      background-color: #563d7c;
      box-shadow: 0 6px 15px rgba(86, 61, 124, 0.8);
      color: white;
      text-decoration: none;
    }
  </style>
</head>
<body>

  <div class="header">
    <h1>Bine ai venit, guest!</h1>
    <a href="logout.php" class="logout-btn">Logout</a>
  </div>

  <div class="container">
    <h3>Anunțuri disponibile</h3>
    <a href="guest_listings.php" class="btn-purple">Vezi anunțuri</a>
    <a href="guest_bookings.php" class="btn-purple">Vezi rezervările mele</a>
    <a href="guest_add_review.php" class="btn-purple">Adaugă review</a>
  </div>

</body>
</html>
