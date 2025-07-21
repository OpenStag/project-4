<?php
// Script to add event_image column to existing database
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

// Check if event_image column already exists
$result = $conn->query("SHOW COLUMNS FROM event LIKE 'event_image'");
if ($result->num_rows == 0) {
    // Add the event_image column
    $sql = "ALTER TABLE event ADD COLUMN event_image VARCHAR(255) DEFAULT NULL AFTER all_seat";
    
    if ($conn->query($sql) === TRUE) {
        echo "Column 'event_image' added successfully!<br>";
        
        // Update existing records to use default image
        $update_sql = "UPDATE event SET event_image = 'default.png' WHERE event_image IS NULL";
        if ($conn->query($update_sql) === TRUE) {
            echo "Existing records updated with default image.<br>";
        }
    } else {
        echo "Error adding column: " . $conn->error . "<br>";
    }
} else {
    echo "Column 'event_image' already exists!<br>";
}

$conn->close();

echo "<br><a href='add_events.php'>Go to Add Events</a>";
echo "<br><a href='display_event.php'>Go to Display Events</a>";
?>
