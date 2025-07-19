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
  <title>Book Now</title>
  <style>
    body {
      height: 100%;
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
      background-color: #222;
    }

    .container {
      background-color: #b1ff9e;
      width: 100vw;
      height: 100vh; 
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .form-box {
      background-color: #55f35b;
      padding: 30px;
      width: 100%;
      max-width: 1000px;
      border-radius: 10px;
    }

    h2 {
      text-align: center;
      margin-top: 0;
    }

    .event-info {
      background-color: #77ff77;
      padding: 15px;
      border-radius: 10px;
      margin-bottom: 20px;
      text-align: center;
    }

    .form-row {
      display: flex;
      gap: 20px;
      margin-bottom: 15px;
    }

    .form-group {
      flex: 1;
      display: flex;
      flex-direction: column;
    }

    input[type=text], input[type=number], input[type=email] {
      padding: 10px;
      border: none;
      border-radius: 5px; 
    }

    input[type=submit] {
      margin-top: 10px;
      padding: 10px 15px;
      border: none;
      background-color: #fff;
      cursor: pointer;
      font-weight: bold;
    }
    input[type=submit]:hover {
      background-color: #333;
      color: #fff;
    }

    .price-info {
      background-color: #44ee55;
      padding: 10px;
      border-radius: 5px;
      margin-top: 10px;
      text-align: center;
      font-weight: bold;
    }
  </style>
</head>
<body>

<div class="container">
  <div class="form-box">
    <h2>Book Now</h2>
    
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