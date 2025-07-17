<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./style1.css">
</head>
<body>
    <div class="container2">
        <div class="header2"> Display Event </div>
        <div class="sidebar2">
            <ul>
                <li><a href="#">Add Event</a></li>
                <li><a href="#">Display Event</a></li>
                <li><a href="#">Book Event</a></li>
            </ul>
        </div>
        <div class="content2">
            <table>
                <tr>
                    <th>
                        id
                    </th>

                    <th>
                        event
                    </th>

                    <th>
                        Date
                    </th>

                    <th>
                        Location
                    </th>

                    <th>
                        Time
                    </th>

                    <th>
                        All Seat
                    </th>

                    <th>
                        Avb Seat
                    </th>

                    <th>
                        Price
                    </th>

                    <th>
                        Delete
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

$sql = "SELECT id, event_name, event_date, event_time, location, ticket_price, all_seat FROM event";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Assuming 'available seats' = all_seat - booked tickets
        $event_id = $row['id'];
        $booked_result = $conn->query("SELECT SUM(buying_tickets) AS booked FROM book_now WHERE id = $event_id");
        $booked_row = $booked_result->fetch_assoc();
        $booked = $booked_row['booked'] ?? 0;
        $available_seat = $row['all_seat'] - $booked;

        echo "<tr>
            <td>{$row['id']}</td>
            <td>{$row['event_name']}</td>
            <td>{$row['event_date']}</td>
            <td>{$row['location']}</td>
            <td>{$row['event_time']}</td>
            <td>{$row['all_seat']}</td>
            <td>$available_seat</td>
            <td>{$row['ticket_price']}</td>
            <td><a href='delete_event.php?id={$row['id']}' onclick=\"return confirm('Are you sure?')\">Delete</a></td>
        </tr>";
    }
} else {
    echo "<tr><td colspan='9'>No events found</td></tr>";
}
$conn->close();
?>
