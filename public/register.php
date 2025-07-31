<?php
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username']);
    $email    = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'];

    if (!$username || !$email || strlen($password) < 6) {
        $errors[] = 'Please fill in all fields and use a password of at least 6 characters.';
    }

    if (empty($errors)) {
        // Check existing email
        $stmt = $mysqli->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $errors[] = 'Email already registered.';
        } else {
            // Insert user
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $ins  = $mysqli->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $ins->bind_param('sss', $username, $email, $hash);
            if ($ins->execute()) {
                redirect('../index.php');
            } else {
                $errors[] = 'Registration failed. Try again.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8">

    <link rel="stylesheet" href="css/styles.css">
<title>Register</title></head>
<body>
<h2>Register</h2>
<?php foreach ($errors as $e): ?>
    <p style="color:red;"><?php echo $e; ?></p>
<?php endforeach; ?>
<form method="post" autocomplete="off">
    <label>Username:<br><input type="text" name="username" required autocomplete="off"></label><br>
    <label>Email:<br><input type="email" name="email" required autocomplete="off"></label><br>
    <label>Password:<br><input type="password" name="password" minlength="6" required autocomplete="off"></label><br>
    <button type="submit">Register</button>
</form>
<p>Already have an account? <a href="../index.php">Login here</a></p>
</body>
</html>