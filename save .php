<!DOCTYPE html>
<html>
<head>
    <title>Booking Success</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f6f8fa;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .success-message {
            background-color: #e6ffed;
            color: #207544;
            border: 1px solid #b7ebc6;
            padding: 28px 40px;
            border-radius: 8px;
            font-size: 1.4em;
            text-align: center;
            font-weight: bold;
            box-shadow: 0 2px 12px rgba(32, 117, 68, 0.10);
        }
    </style>
</head>
<body>
    <?php
    $conn = new mysqli("localhost", "root", "", "event_database");
    if ($conn->connect_error) {
     
        exit();
    }

    if (
        empty($_POST['fname']) ||
        empty($_POST['lname']) ||
        empty($_POST['email']) ||
        empty($_POST['address']) ||
        empty($_POST['tickets'])
    ) {
        
        exit();
    }

    $fname = $conn->real_escape_string($_POST['fname']);
    $lname = $conn->real_escape_string($_POST['lname']);
    $email = $conn->real_escape_string($_POST['email']);
    $address = $conn->real_escape_string($_POST['address']);
    $tickets = (int)$_POST['tickets'];

    $sql = "INSERT INTO book_now (first_name, last_name, email, address, buying_tickets) VALUES ('$fname', '$lname', '$email', '$address', $tickets)";

    if ($conn->query($sql) === TRUE) {
        echo '<div class="success-message">Booking Successful!<br> </div>';
    }
  
    $conn->close();
    ?>
</body>
</html>
