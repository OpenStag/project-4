<?php
// Check if required POST data exists
if (!isset($_POST['event_id']) || !isset($_POST['tickets']) || !isset($_POST['fname']) || !isset($_POST['lname']) || !isset($_POST['email']) || !isset($_POST['address'])) {
    echo "Missing required data. Please go back and fill the form properly.";
    echo "<br><a href='events.php'>Go back to event selection</a>";
    exit();
}

$conn = new mysqli("localhost", "root", "", "event_database");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$event_id = (int)$_POST['event_id'];
$tickets = (int)$_POST['tickets'];

// Validate ticket count
if ($tickets <= 0) {
    die("Invalid number of tickets");
}

// Get event details
$stmt = $conn->prepare("SELECT event_name, ticket_price, all_seat FROM event WHERE id = ?");
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Event not found");
}

$event = $result->fetch_assoc();
$ticket_price = $event['ticket_price'];
$total_price = $ticket_price * $tickets;

// Check if enough seats available
if ($tickets > $event['all_seat']) {
    die("Not enough seats available. Only " . $event['all_seat'] . " seats remaining.");
}

$stmt->close();
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
        .event-summary {
            background-color: #77ff77;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
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
        <h1>Payment Details</h1>
        
        <div class="event-summary">
            <h3><?php echo htmlspecialchars($event['event_name']); ?></h3>
            <p><strong>Tickets:</strong> <?php echo $tickets; ?></p>
            <p><strong>Price per ticket:</strong> LKR <?php echo number_format($ticket_price, 2); ?></p>
            <p><strong>Total Price:</strong> LKR <?php echo number_format($total_price, 2); ?></p>
        </div>
        
        <form method="post" action="save.php">
            <!-- Hidden fields to pass data -->
            <input type="hidden" name="event_id" value="<?php echo $event_id; ?>">
            <input type="hidden" name="fname" value="<?php echo htmlspecialchars($_POST['fname']); ?>">
            <input type="hidden" name="lname" value="<?php echo htmlspecialchars($_POST['lname']); ?>">
            <input type="hidden" name="email" value="<?php echo htmlspecialchars($_POST['email']); ?>">
            <input type="hidden" name="address" value="<?php echo htmlspecialchars($_POST['address']); ?>">
            <input type="hidden" name="tickets" value="<?php echo $tickets; ?>">
            <input type="hidden" name="total_price" value="<?php echo $total_price; ?>">

            <label>Card Number</label>
            <input type="text" name="card_number" required pattern="\d{16}" placeholder="1234 5678 9012 3456" maxlength="16">

            <label>Expiry Date</label>
            <input type="text" name="exp_date" required placeholder="MM/YY" pattern="\d{2}/\d{2}" maxlength="5">

            <label>CVV</label>
            <input type="text" name="cvv" required pattern="\d{3,4}" placeholder="123" maxlength="4">

            <input type="submit" value="Complete Booking">
        </form>
    </div>
</body>
</html>