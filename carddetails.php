<?php
$conn = new mysqli("localhost", "root", "", "event_database");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$tickets = (int)$_POST['tickets'];

// Get event details
$stmt = $conn->prepare("SELECT event_name, ticket_price, all_seat FROM event WHERE id = ?");
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();


$event = $result->fetch_assoc();
$ticket_price = $event['ticket_price'];
$total_price = $ticket_price * $tickets;


if ($tickets > $event['all_seat']) {
    die("Not enough seats available. Only " . $event['all_seat'] . " seats remaining.");
}

$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Card Details</title>
    <style>
        body {
            background-color: #aaffc7;
            font-family: Arial, sans-serif;
        }
        .container {
            background-color: #44ee55;
            border-radius: 15px;
            padding: 40px;
            max-width: 500px;
            margin: 40px auto;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        h1 {
            text-align: center;
            font-size: 2.5em;
        }
        label {
            font-weight: bold;
            margin-top: 10px;
            display: block;
        }
        input[type="text"], input[type="email"], input[type="number"] {
            width: 100%;
            padding: 10px;
            margin: 6px 0 15px 0;
            border: none;
            border-radius: 5px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #fff;
            color: #222;
            padding: 10px 25px;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 10px;
        }
        input[type="submit"]:hover {
            background-color: #e0ffe0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Book Now</h1>
        <h3>Total Price: <?php echo $total_price; ?> LKR</h3>
        <form method="post" action="save .php">
          
             <input type="hidden" name="fname" value="<?php echo isset($_POST['fname']) ? htmlspecialchars($_POST['fname']) : ''; ?>">
            <input type="hidden" name="lname" value="<?php echo isset($_POST['lname']) ? htmlspecialchars($_POST['lname']) : ''; ?>">
            <input type="hidden" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
            <input type="hidden" name="address" value="<?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?>">
            <input type="hidden" name="tickets" value="<?php echo isset($_POST['tickets']) ? htmlspecialchars($_POST['tickets']) : ''; ?>"> 

            <label>Card Number</label>
            <input type="text" name="card_number" required pattern="\d{16}" placeholder="Card Number">

            <label>Exp Date</label>
            <input type="text" name="exp_date" required placeholder="MM/YY">

            <label>CVV</label>
            <input type="text" name="cvv" required pattern="\d{3,4}" placeholder="CVV">

            <input type="submit" value="Book now">
        </form>
    </div>
</body>
</html>