<?php
session_start();

$conn = new mysqli("localhost", "root", "", "event_database");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];


    $email = mysqli_real_escape_string($conn, $email);
    $password = mysqli_real_escape_string($conn, $password);

   
    $sql = "SELECT * FROM admin_details WHERE email='$email' AND password='$password'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) === 1) {
        $_SESSION['email'] = $email;
        header("Location: add_event.php");
        exit();
    } else {
        $error = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="css/loging.css">
    
        
</head>
<body>
<div class="login-box">
    <h2>Login</h2>
    <form method="POST">
        <div>
            <input type="text" name="email" placeholder="Email" required>
        </div>
        <div>
            <input type="password" name="password" placeholder="Password" required>
        </div>
        <button type="submit">Login</button>
    </form>
    <?php if (!empty($error)) echo "<div class='error'>$error</div>"; ?>
</div>
</body>
</html>
