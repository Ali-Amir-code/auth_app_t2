<?php
session_start();
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'];

    if (!$email || !$password) {
        $errors[] = 'Please fill in both fields.';
    } else {
        $stmt = $mysqli->prepare("SELECT id, username, password FROM users WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->bind_result($id, $username, $hash);
        if ($stmt->fetch() && password_verify($password, $hash)) {
            $_SESSION['user_id']   = $id;
            $_SESSION['username'] = $username;
            redirect('public/dashboard.php');
        } else {
            $errors[] = 'Invalid email or password.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8">

    <link rel="stylesheet" href="public/css/styles.css">
<title>Login</title></head>
<body>
<h2>Login</h2>
<?php foreach ($errors as $e): ?>
    <p style="color:red;"><?php echo $e; ?></p>
<?php endforeach; ?>
<form method="post" autocomplete="off">
    <label>Email:<br><input type="email" name="email" required autocomplete="off"></label><br>
    <label>Password:<br><input type="password" name="password" required autocomplete="off"></label><br>
    <button type="submit">Login</button>
</form>
<p>No account? <a href="public/register.php">Register here</a></p>
</body>
</html>