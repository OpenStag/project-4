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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Management - Payment Details</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
            position: relative;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.05)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.05)"/><circle cx="50" cy="10" r="0.5" fill="rgba(255,255,255,0.03)"/><circle cx="10" cy="60" r="0.5" fill="rgba(255,255,255,0.03)"/><circle cx="90" cy="40" r="0.5" fill="rgba(255,255,255,0.03)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            pointer-events: none;
            z-index: -1;
        }

        .container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(15px);
            border-radius: 20px;
            padding: 40px;
            max-width: 600px;
            margin: 40px auto;
            box-shadow: 
                0 25px 50px rgba(0, 0, 0, 0.15),
                0 10px 20px rgba(0, 0, 0, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.2);
            animation: slideUp 0.6s ease-out;
            position: relative;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        h1 {
            text-align: center;
            font-size: 2.5em;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 30px;
            position: relative;
        }

        h1::before {
            content: '💳';
            position: absolute;
            left: -50px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 0.8em;
        }

        .event-summary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .event-summary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transition: left 0.5s;
        }

        .event-summary:hover::before {
            left: 100%;
        }

        .event-summary h3 {
            font-size: 1.5em;
            margin-bottom: 15px;
            font-weight: 700;
        }

        .event-summary p {
            margin: 8px 0;
            font-size: 1.1em;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .event-summary p:last-child {
            font-size: 1.3em;
            font-weight: 700;
            border-top: 2px solid rgba(255, 255, 255, 0.3);
            padding-top: 15px;
            margin-top: 15px;
        }

        label {
            font-weight: 600;
            color: #2c3e50;
            margin-top: 20px;
            margin-bottom: 8px;
            display: block;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        input[type="text"], 
        input[type="email"], 
        input[type="number"] {
            width: 100%;
            padding: 15px;
            margin-bottom: 20px;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        input[type="text"]:focus, 
        input[type="email"]:focus, 
        input[type="number"]:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            transform: translateY(-2px);
        }

        /* Credit Card Specific Styling */
        .card-input-group {
            position: relative;
        }

        .card-input-group input {
            padding-left: 50px;
        }

        .card-input-group::before {
            content: '💳';
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 20px;
            z-index: 10;
        }

        .expiry-cvv-row {
            display: flex;
            gap: 20px;
        }

        .expiry-cvv-row .form-group {
            flex: 1;
        }

        input[type="submit"] {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            padding: 18px 40px;
            border: none;
            border-radius: 12px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            width: 100%;
            margin-top: 20px;
            position: relative;
            overflow: hidden;
        }

        input[type="submit"]::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        input[type="submit"]:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(40, 167, 69, 0.4);
        }

        input[type="submit"]:hover::before {
            left: 100%;
        }

        /* Security Badge */
        .security-info {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-left: 4px solid #28a745;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
            font-size: 14px;
            color: #6c757d;
        }

        .security-info::before {
            content: '🔒 ';
            color: #28a745;
            font-weight: bold;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                margin: 20px auto;
                padding: 30px 20px;
            }

            h1 {
                font-size: 2em;
            }

            h1::before {
                position: static;
                display: block;
                margin-bottom: 10px;
            }

            .expiry-cvv-row {
                flex-direction: column;
                gap: 0;
            }

            .event-summary p {
                flex-direction: column;
                gap: 5px;
                text-align: center;
            }
        }

        @media (max-width: 480px) {
            .container {
                margin: 10px;
                padding: 25px 15px;
            }

            h1 {
                font-size: 1.8em;
            }

            input[type="submit"] {
                padding: 15px 30px;
                font-size: 16px;
            }
        }

        /* Navigation Link */
        .nav-link {
            position: fixed;
            top: 30px;
            left: 30px;
            background: rgba(255, 255, 255, 0.9);
            color: #667eea;
            padding: 12px 20px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            z-index: 1000;
            backdrop-filter: blur(10px);
        }

        .nav-link:hover {
            background: #667eea;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }
    </style>
</head>
<body>
    <a href="javascript:history.back()" class="nav-link">← Back</a>

    <div class="container">
        <h1>Secure Payment</h1>
        
        <div class="event-summary">
            <h3><?php echo htmlspecialchars($event['event_name']); ?></h3>
            <p><span>Tickets:</span><span><?php echo $tickets; ?></span></p>
            <p><span>Price per ticket:</span><span>LKR <?php echo number_format($ticket_price, 2); ?></span></p>
            <p><span>Total Amount:</span><span>LKR <?php echo number_format($total_price, 2); ?></span></p>
        </div>

        <div class="security-info">
            Your payment information is encrypted and secure. We never store your card details.
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

            <div class="card-input-group">
                <label>Card Number</label>
                <input type="text" name="card_number" required pattern="\d{16}" placeholder="1234 5678 9012 3456" maxlength="19" oninput="formatCardNumber(this)">
            </div>

            <div class="expiry-cvv-row">
                <div class="form-group">
                    <label>Expiry Date</label>
                    <input type="text" name="exp_date" required placeholder="MM/YY" pattern="\d{2}/\d{2}" maxlength="5" oninput="formatExpiry(this)">
                </div>
                <div class="form-group">
                    <label>CVV</label>
                    <input type="text" name="cvv" required pattern="\d{3,4}" placeholder="123" maxlength="4" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                </div>
            </div>

            <input type="submit" value="🔒 Complete Secure Payment">
        </form>
    </div>

    <script>
        function formatCardNumber(input) {
            let value = input.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
            let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
            input.value = formattedValue;
        }

        function formatExpiry(input) {
            let value = input.value.replace(/\D/g, '');
            if (value.length >= 2) {
                value = value.substring(0, 2) + '/' + value.substring(2, 4);
            }
            input.value = value;
        }
    </script>
</body>
</html>