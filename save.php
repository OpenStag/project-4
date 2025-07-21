<!DOCTYPE html>
<html>
<head>
    <title>Event Management - Booking Success</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0;
            padding: 20px;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }
        
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="10" r="0.5" fill="rgba(255,255,255,0.05)"/><circle cx="10" cy="60" r="0.5" fill="rgba(255,255,255,0.05)"/><circle cx="90" cy="40" r="0.5" fill="rgba(255,255,255,0.05)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            pointer-events: none;
        }
        
        .success-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 40px 50px;
            box-shadow: 
                0 20px 40px rgba(0, 0, 0, 0.1),
                0 10px 20px rgba(0, 0, 0, 0.05),
                inset 0 1px 0 rgba(255, 255, 255, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.2);
            position: relative;
            z-index: 1;
            max-width: 500px;
            width: 100%;
            animation: slideUp 0.6s ease-out;
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
        
        .success-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #4CAF50, #45a049);
            border-radius: 50%;
            margin: 0 auto 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 16px rgba(76, 175, 80, 0.3);
            animation: checkmark 0.8s ease-out 0.3s both;
        }
        
        @keyframes checkmark {
            0% {
                transform: scale(0);
                opacity: 0;
            }
            50% {
                transform: scale(1.1);
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }
        
        .success-icon::after {
            content: '✓';
            color: white;
            font-size: 40px;
            font-weight: bold;
        }
        
        .success-message {
            color: #2c3e50;
            font-size: 2em;
            text-align: center;
            font-weight: 600;
            margin-bottom: 15px;
            line-height: 1.3;
        }
        
        .success-subtitle {
            color: #7f8c8d;
            font-size: 1.1em;
            text-align: center;
            margin-bottom: 30px;
            line-height: 1.5;
        }
        
        .booking-details {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-radius: 12px;
            padding: 20px;
            margin-top: 25px;
            border-left: 4px solid #667eea;
        }
        
        .detail-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding: 5px 0;
        }
        
        .detail-label {
            color: #6c757d;
            font-weight: 500;
        }
        
        .detail-value {
            color: #2c3e50;
            font-weight: 600;
        }
        
        .action-buttons {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            justify-content: center;
        }
        
        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            transition: all 0.3s ease;
            min-width: 120px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(102, 126, 234, 0.4);
        }
        
        .btn-secondary {
            background: rgba(108, 117, 125, 0.1);
            color: #6c757d;
            border: 1px solid rgba(108, 117, 125, 0.3);
        }
        
        .btn-secondary:hover {
            background: rgba(108, 117, 125, 0.2);
            transform: translateY(-1px);
        }
        
        @media (max-width: 600px) {
            .success-container {
                margin: 20px;
                padding: 30px 25px;
            }
            
            .success-message {
                font-size: 1.6em;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
            }
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
        empty($_POST['event_id']) ||
        empty($_POST['fname']) ||
        empty($_POST['lname']) ||
        empty($_POST['email']) ||
        empty($_POST['address']) ||
        empty($_POST['tickets'])
    ) {
        
        exit();
    }

    $eid = $conn->real_escape_string($_POST['event_id']);
    
    // Get the event name from the database
    $ename_query = "SELECT event_name FROM event WHERE id = $eid";
    $ename_result = $conn->query($ename_query);
    $ename = "";
    if ($ename_result && $ename_result->num_rows > 0) {
        $row = $ename_result->fetch_assoc();
        $ename = $conn->real_escape_string($row['event_name']);
    }
    
    $fname = $conn->real_escape_string($_POST['fname']);
    $lname = $conn->real_escape_string($_POST['lname']);
    $email = $conn->real_escape_string($_POST['email']);
    $address = $conn->real_escape_string($_POST['address']);
    $tickets = (int)$_POST['tickets'];

    $sql = "INSERT INTO book_now (first_name, last_name, email, address, buying_tickets,event_name) VALUES ('$fname', '$lname', '$email', '$address', $tickets, '$ename')";

    if ($conn->query($sql) === TRUE) {
        echo '<div class="success-container">';
        echo '<div class="success-icon"></div>';
        echo '<div class="success-message">Booking Confirmed!</div>';
        echo '<div class="success-subtitle">Your event booking has been successfully processed.</div>';
        echo '<div class="booking-details">';
        echo '<div class="detail-item">';
        echo '<span class="detail-label">Event:</span>';
        echo '<span class="detail-value">' . htmlspecialchars($ename) . '</span>';
        echo '</div>';
        echo '<div class="detail-item">';
        echo '<span class="detail-label">Name:</span>';
        echo '<span class="detail-value">' . htmlspecialchars($fname . ' ' . $lname) . '</span>';
        echo '</div>';
        echo '<div class="detail-item">';
        echo '<span class="detail-label">Email:</span>';
        echo '<span class="detail-value">' . htmlspecialchars($email) . '</span>';
        echo '</div>';
        echo '<div class="detail-item">';
        echo '<span class="detail-label">Tickets:</span>';
        echo '<span class="detail-value">' . $tickets . '</span>';
        echo '</div>';
        echo '</div>';
        echo '<div class="action-buttons">';
        echo '<a href="display_event.php" class="btn btn-primary">View More Events</a>';
        echo '<a href="booknow.php" class="btn btn-secondary">Book Another</a>';
        echo '</div>';
        echo '</div>';
    }
  
    $conn->close();
    ?>
</body>
</html>
