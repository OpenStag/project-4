<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Management - Display Events</title>
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
                    <h2 class="card-title">All Events</h2>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Event Name</th>
                            <th>Date</th>
                            <th>Location</th>
                            <th>Time</th>
                            <th>Total Seats</th>
                            <th>Available</th>
                            <th>Price</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
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

$sql = "SELECT id, event_name, event_date, event_time, location, ticket_price, all_seat, event_image FROM event";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Assuming 'available seats' = all_seat - booked tickets
        $event_id = $row['id'];
        $booked_result = $conn->query("SELECT SUM(buying_tickets) AS booked FROM book_now WHERE id = $event_id");
        $booked_row = $booked_result->fetch_assoc();
        $booked = $booked_row['booked'] ?? 0;
        $available_seat = $row['all_seat'] - $booked;

        $image_path = !empty($row['event_image']) ? 'images/events/' . $row['event_image'] : 'images/events/default.png';
        
        echo "<tr>
            <td>{$row['id']}</td>
            <td><img src='$image_path' alt='Event Image' class='event-thumbnail'></td>
            <td><strong>{$row['event_name']}</strong></td>
            <td>{$row['event_date']}</td>
            <td>{$row['location']}</td>
            <td>{$row['event_time']}</td>
            <td><span class='badge'>{$row['all_seat']}</span></td>
            <td><span class='badge " . ($available_seat > 0 ? 'badge-success' : 'badge-danger') . "'>$available_seat</span></td>
            <td><strong>\${$row['ticket_price']}</strong></td>
            <td><a href='delete_event.php?id={$row['id']}' class='btn-danger' onclick=\"return confirm('Are you sure you want to delete this event?')\">🗑️ Delete</a></td>
        </tr>";
    }
} else {
    echo "<tr><td colspan='10' class='no-data'>No events found. <a href='add_events.php'>Add your first event!</a></td></tr>";
}
$conn->close();
?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <style>
        .badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            color: white;
            background: linear-gradient(135deg, #667eea, #764ba2);
        }
        
        .badge-success {
            background: linear-gradient(135deg, #28a745, #20c997) !important;
        }
        
        .badge-danger {
            background: linear-gradient(135deg, #dc3545, #c82333) !important;
        }
        
        .no-data {
            text-align: center;
            color: #6c757d;
            font-style: italic;
            padding: 40px !important;
        }
        
        .no-data a {
            color: #667eea;
            text-decoration: none;
            font-weight: bold;
        }
        
        .event-thumbnail {
            width: 60px;
            height: 40px;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        
        .event-thumbnail:hover {
            transform: scale(1.5);
            z-index: 1000;
            position: relative;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }
    </style>
</body>
</html>
