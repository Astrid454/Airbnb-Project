<!DOCTYPE html>
<html lang="ro">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Airbnb - Bine ai venit!</title>
  <style>
    /* Reset simplu */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    body {
      min-height: 100vh;
      background: linear-gradient(135deg, #4b0082, #7b2cbf);
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      color: #fff;
      padding: 20px;
    }

    header {
      font-size: 3rem;
      font-weight: 700;
      letter-spacing: 2px;
      text-shadow: 0 2px 8px rgba(0, 0, 0, 0.5);
      margin-bottom: 30px;
    }

    main {
      background: rgba(255, 255, 255, 0.1);
      padding: 40px 50px;
      border-radius: 20px;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
      text-align: center;
      max-width: 450px;
      width: 100%;
    }

    main p {
      font-size: 1.2rem;
      margin-bottom: 30px;
      line-height: 1.5;
      color: #e0d7f5;
    }

    .btn {
      display: inline-block;
      padding: 15px 40px;
      margin: 10px;
      font-size: 1.2rem;
      border: none;
      border-radius: 50px;
      cursor: pointer;
      transition: background-color 0.3s ease, box-shadow 0.3s ease;
      text-decoration: none;
      color: white;
      font-weight: 600;
      box-shadow: 0 4px 15px rgba(123, 44, 191, 0.7);
    }

    .btn-login {
      background-color: #2e0854;
    }

    .btn-login:hover {
      background-color: #7b2cbf;
      box-shadow: 0 6px 20px rgba(123, 44, 191, 1);
    }

    .btn-signup {
      background-color: #7b2cbf;
    }

    .btn-signup:hover {
      background-color: #2e0854;
      box-shadow: 0 6px 20px rgba(46, 8, 84, 1);
    }

    footer {
      margin-top: 50px;
      font-size: 0.9rem;
      color: #b2a1db;
      text-align: center;
    }
  </style>
</head>
<body>
  <header>Airbnb</header>
  <main>
    <p>Bine ai venit pe platforma noastră Airbnb! Cazează-te sau închiriază proprietăți unice oriunde în lume.</p>
    <a href="login.html" class="btn btn-login">Log In</a>
    <a href="signup.html" class="btn btn-signup">Sign Up</a>
  </main>
  <footer>
    &copy; 2025 Airbnb România. Toate drepturile rezervate.
  </footer>
</body>
</html>
