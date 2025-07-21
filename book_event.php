<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Management - Booking History</title>
    <link rel="stylesheet" href="./style1.css">
</head>
<body>
    <div class="container2">
        <div class="header2">🎯 Event Management System</div>
        <div class="sidebar2">
            <ul>
                <li><a href="./add_events.php">➕ Add Event</a></li>
                <li><a href="./display_event.php">📋 Display Events</a></li>
                <li><a href="./book_event.php">🎫 Book Event</a></li>
            </ul>
        </div>
        <div class="content2">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Event Bookings</h2>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Event Name</th>
                            <th>First Name</th>

                    <th>
                        LasttName
                    </th>

                    <th>
                        Email
                    </th>

                    <th>
                        Address
                    </th>

                    <th>
                        Buying Ticket
                    </th>
                    
                </tr>
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "event_database";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id,event_name,first_name, last_name, email, address, buying_tickets FROM book_now";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        

        echo "<tr>
            <td>{$row['id']}</td>
            <td>{$row['event_name']}</td>
            <td>{$row['first_name']}</td>
            <td>{$row['last_name']}</td>
            <td>{$row['email']}</td>
            <td>{$row['address']}</td>
            <td>{$row['buying_tickets']}</td>
        </tr>";
    }
} else {
    echo "<tr><td colspan='9'>No events found</td></tr>";
}
$conn->close();
?>
