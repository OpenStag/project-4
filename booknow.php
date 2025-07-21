<?php
// Check if event_id is provided
if (!isset($_GET['event_id']) || empty($_GET['event_id'])) {
    echo "No event selected. Please go back and select an event.";
    echo "<br><a href='events.php'>Select an Event</a>";
    exit();
}

$event_id = (int)$_GET['event_id'];

// Get event details from database
$conn = new mysqli("localhost", "root", "", "event_database");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$stmt = $conn->prepare("SELECT event_name, event_date, event_time, location, ticket_price, all_seat FROM event WHERE id = ?");
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Event not found. Please go back and select a valid event.";
    echo "<br><a href='events.php'>Select an Event</a>";
    exit();
}

$event = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Event Management - Book Now</title>
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
      overflow-x: hidden;
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
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      padding: 20px;
    }

    .form-box {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(15px);
      padding: 40px;
      width: 100%;
      max-width: 900px;
      border-radius: 20px;
      box-shadow: 
        0 25px 50px rgba(0, 0, 0, 0.15),
        0 10px 20px rgba(0, 0, 0, 0.1),
        inset 0 1px 0 rgba(255, 255, 255, 0.6);
      border: 1px solid rgba(255, 255, 255, 0.2);
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

    h2 {
      text-align: center;
      margin-bottom: 30px;
      font-size: 2.5em;
      font-weight: 700;
      color: #2c3e50;
      position: relative;
    }

    h2::after {
      content: '🎫';
      position: absolute;
      right: -50px;
      top: 50%;
      transform: translateY(-50%);
      font-size: 0.8em;
    }

    .event-info {
      background: linear-gradient(135deg, #667eea, #764ba2);
      color: white;
      padding: 25px;
      border-radius: 15px;
      margin-bottom: 30px;
      text-align: center;
      position: relative;
      overflow: hidden;
    }

    .event-info::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
      transition: left 0.5s;
    }

    .event-info:hover::before {
      left: 100%;
    }

    .event-info h3 {
      font-size: 1.8em;
      margin-bottom: 15px;
      font-weight: 700;
    }

    .event-info p {
      margin: 8px 0;
      font-size: 1.1em;
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 10px;
    }

    .event-info p strong {
      font-weight: 600;
    }

    .form-row {
      display: flex;
      gap: 25px;
      margin-bottom: 25px;
    }

    .form-group {
      flex: 1;
      display: flex;
      flex-direction: column;
    }

    label {
      font-weight: 600;
      color: #2c3e50;
      margin-bottom: 8px;
      font-size: 14px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    input[type=text], 
    input[type=number], 
    input[type=email] {
      padding: 15px;
      border: 2px solid #e9ecef;
      border-radius: 10px;
      font-size: 16px;
      transition: all 0.3s ease;
      background: #f8f9fa;
    }

    input[type=text]:focus, 
    input[type=number]:focus, 
    input[type=email]:focus {
      outline: none;
      border-color: #667eea;
      background: white;
      box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
      transform: translateY(-2px);
    }

    input[type=submit] {
      background: linear-gradient(135deg, #667eea, #764ba2);
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

    input[type=submit]::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
      transition: left 0.5s;
    }

    input[type=submit]:hover {
      transform: translateY(-3px);
      box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
    }

    input[type=submit]:hover::before {
      left: 100%;
    }

    .price-info {
      background: linear-gradient(135deg, #28a745, #20c997);
      color: white;
      padding: 15px;
      border-radius: 10px;
      margin-top: 15px;
      text-align: center;
      font-weight: 600;
      font-size: 16px;
      box-shadow: 0 6px 20px rgba(40, 167, 69, 0.3);
    }

    .price-info::before {
      content: '💰 ';
      margin-right: 8px;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      .form-row {
        flex-direction: column;
        gap: 15px;
      }

      .form-box {
        padding: 30px 20px;
        margin: 10px;
      }

      h2 {
        font-size: 2em;
      }

      h2::after {
        position: static;
        display: block;
        margin-top: 10px;
      }

      .event-info p {
        flex-direction: column;
        gap: 5px;
      }
    }

    @media (max-width: 480px) {
      .container {
        padding: 10px;
      }

      .form-box {
        padding: 25px 15px;
      }

      h2 {
        font-size: 1.8em;
      }

      input[type=submit] {
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

<a href="display_card.php" class="nav-link">← Back to Events</a>

<div class="container">
  <div class="form-box">
    <h2>Book Your Event</h2>
    
    <!-- Display Event Information -->
    <div class="event-info">
      <h3><?php echo htmlspecialchars($event['event_name']); ?></h3>
      <p><strong>Date:</strong> <?php echo date('F j, Y', strtotime($event['event_date'])); ?></p>
      <p><strong>Time:</strong> <?php echo date('g:i A', strtotime($event['event_time'])); ?></p>
      <p><strong>Location:</strong> <?php echo htmlspecialchars($event['location']); ?></p>
      <p><strong>Price per ticket:</strong> LKR <?php echo number_format($event['ticket_price'], 2); ?></p>
      <p><strong>Available seats:</strong> <?php echo $event['all_seat']; ?></p>
    </div>
    
    <form action="carddetails.php" method="POST">
      <!-- Hidden field to pass event_id -->
      <input type="hidden" name="event_id" value="<?php echo $event_id; ?>">
      
      <div class="form-row">
        <div class="form-group">
          <label>First Name</label>
          <input type="text" name="fname" placeholder="First Name" required>
        </div>
        <div class="form-group">
          <label>Last Name</label>
          <input type="text" name="lname" placeholder="Last Name" required>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label>Email</label>
          <input type="email" name="email" placeholder="Email Address" required>
        </div>
        <div class="form-group">
          <label>Address</label>
          <input type="text" name="address" placeholder="Home Address" required>
        </div>
      </div>
      
      <div class="form-row">
        <div class="form-group">
          <label>How many tickets</label>
          <input type="number" name="tickets" placeholder="Number of tickets" min="1" max="<?php echo $event['all_seat']; ?>" required>
          <div class="price-info">
            Price per ticket: LKR <?php echo number_format($event['ticket_price'], 2); ?>
          </div>
        </div>
        <div class="form-group">
          <!-- Empty for spacing -->
        </div>
      </div>

      <input type="submit" name="continue" value="Continue">
    </form>
  </div>
</div>

</body>
</html>