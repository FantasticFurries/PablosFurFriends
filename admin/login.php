<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="login.css">
</head>
<body>
  <div class="container">
    <div class="login-card">
      <img src="img/logo_pff.png" alt="Logo" class="logo">
      <h2>Admin Login</h2>
      <form action="login_q.php" method="post">
        <input type="text" placeholder="Username" name="username" required>
        <input type="password" placeholder="Password" name="password" required>
        <button type="submit" name="login">Login</button>
      </form>
    </div>
  </div>
</body>
</html>
