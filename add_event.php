<?php
// 1. Connect to MySQL
$servername = "localhost"; // change if needed
$username = "root";        // change if needed
$password = "";            // change if needed
$database = "event_database";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 2. Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $event_name   = $_POST['event_name'];
    $event_date   = $_POST['event_date'];
    $event_time   = $_POST['time'];
    $location     = $_POST['location'];
    $ticket_price = $_POST['ticket_price'];
    $seats        = $_POST['seats'];

    // Optional: image upload handling (if you plan to use it later)
    // For now, ignore the image field or save a placeholder

    // 3. Insert data into `event` table
    $stmt = $conn->prepare("INSERT INTO event (event_name, event_date, event_time, location, ticket_price, all_seat) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssdi", $event_name, $event_date, $event_time, $location, $ticket_price, $seats);

    if ($stmt->execute()) {
        echo "<script>alert('Event added successfully!');</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Add Event</title>
    <style>
        /* [Your same CSS styles here] */
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .container {
            display: flex;
            height: 100vh;
        }

        .sidebar {
            width: 200px;
            background-color: #4ade80;
            padding: 20px;
            color: black;
            font-weight: bold;
        }

        .sidebar a {
            display: block;
            margin-bottom: 20px;
            text-decoration: none;
            color: black;
        }

        .content {
            flex: 1;
            background-color: #bbf7d0;
            padding: 40px;
        }

        h2 {
            text-align: center;
            color: black;
        }

        form {
            max-width: 700px;
            margin: 0 auto;
        }

        .form-group {
            display: flex;
            margin-bottom: 20px;
            justify-content: space-between;
        }

        .form-group input {
            width: 48%;
            padding: 10px;
            font-size: 16px;
        }

        .full-width {
            width: 100%;
            margin-bottom: 20px;
        }

        .full-width input {
            width: 100%;
            padding: 10px;
            font-size: 16px;
        }

        .submit-btn {
            background-color: #4ade80;
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            font-weight: bold;
            cursor: pointer;
        }

        .submit-btn:hover {
            background-color: #22c55e;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="sidebar">
            <a href="add_event.php">Add Event</a>
            <a href="display_event.php">Display Event</a>
            <a href="book_event.php">Book Event</a>
        </div>
        <div class="content">
            <h2>Add Event</h2>
            <form method="post" action="add_event.php" enctype="multipart/form-data">
                <div class="form-group">
                    <input type="text" name="event_name" placeholder="Event Name" required>
                    <input type="date" name="event_date" required>
                </div>
                <div class="form-group">
                    <input type="text" name="time" placeholder="Time (e.g. 18:00:00)" required>
                    <input type="text" name="location" placeholder="Location" required>
                </div>
                <div class="form-group">
                    <input type="number" step="0.01" name="ticket_price" placeholder="Ticket Price" required>
                    <input type="number" name="seats" placeholder="Total Seats" required>
                </div>
                <div class="full-width">
                    <input type="file" name="image">
                </div>
                <button type="submit" class="submit-btn">Add Event</button>
            </form>
        </div>
    </div>
</body>

</html>