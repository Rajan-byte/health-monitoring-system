<?php
require 'config.php';
session_start();

// If already logged in, redirect
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if ($email && $password) {
        $stmt = $pdo->prepare("SELECT id, email, password, is_admin, first_name, last_name FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Store session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['is_admin'] = $user['is_admin'];
            $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];

            header("Location: index.php");
            exit;
        } else {
            $error = "Invalid email or password.";
        }
    } else {
        $error = "Please fill in both fields.";
    }
}
?>

<?php require 'header.php'; ?>

<div class="card">
  <h2>Login</h2>
  <p class="small">Enter your credentials to access the system.</p>

  <?php if ($error): ?>
    <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
  <?php endif; ?>

  <form method="post" action="login.php">
    <div class="form-row">
      <label>Email</label>
      <input type="email" name="email" required>
    </div>
    <div class="form-row">
      <label>Password</label>
      <input type="password" name="password" required>
    </div>
    <button type="submit">Login</button>
  </form>

  <p class="small">Don't have an account? <a href="signup.php">Sign up here</a>.</p>
</div>

<?php require 'footer.php'; ?>
