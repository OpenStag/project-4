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

    // Handle image upload
    $event_image = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'images/events/';
        
        // Create directory if it doesn't exist
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $file_tmp = $_FILES['image']['tmp_name'];
        $file_name = $_FILES['image']['name'];
        $file_size = $_FILES['image']['size'];
        $file_type = $_FILES['image']['type'];
        
        // Get file extension
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        // Allowed file types
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (in_array($file_ext, $allowed_types)) {
            // Generate unique filename
            $new_filename = uniqid('event_') . '.' . $file_ext;
            $upload_path = $upload_dir . $new_filename;
            
            if (move_uploaded_file($file_tmp, $upload_path)) {
                $event_image = $new_filename;
            } else {
                echo "<script>alert('Error uploading image.');</script>";
            }
        } else {
            echo "<script>alert('Invalid file type. Please upload JPG, PNG, GIF, or WebP images only.');</script>";
        }
    } else {
        // Set default image if no image uploaded
        $event_image = 'default.png';
    }

    // 3. Insert data into `event` table with image
    $stmt = $conn->prepare("INSERT INTO event (event_name, event_date, event_time, location, ticket_price, all_seat, event_image) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssis", $event_name, $event_date, $event_time, $location, $ticket_price, $seats, $event_image);

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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./style1.css">
    <style>
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

        /* File input styling */
        .file-input-wrapper {
            position: relative;
            display: inline-block;
            width: 100%;
        }

        .file-input {
            width: 100%;
            padding: 15px;
            border: 2px dashed #667eea;
            border-radius: 10px;
            background: #f8f9fa;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .file-input:hover {
            border-color: #764ba2;
            background: #e9ecef;
        }

        .file-input-info {
            text-align: center;
            margin-top: 10px;
        }

        .file-input-info span {
            display: block;
            color: #667eea;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .file-input-info small {
            color: #6c757d;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container2">
        <div class="header2"> Add Event </div>
        <div class="sidebar2">
            <ul>
                <li><a href="./add_events.php">Add Event</a></li>
                <li><a href="./display_event.php">Display Event</a></li>
                <li><a href="./book_event.php">Book Event</a></li>
            </ul>
        </div>
        <div class="content2">
            <form method="post" action="add_events.php" enctype="multipart/form-data">
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
                    <div class="file-input-wrapper">
                        <input type="file" name="image" class="file-input" accept="image/*" onchange="previewImage(this)">
                        <div class="file-input-info">
                            <span>📷 Choose an image for your event</span>
                            <small>Supported formats: JPG, PNG, GIF, WebP (Max 5MB)</small>
                        </div>
                        <div id="image-preview" style="display: none; margin-top: 15px; text-align: center;">
                            <img id="preview-img" src="" alt="Preview" style="max-width: 200px; max-height: 150px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                            <p style="margin-top: 10px; color: #28a745; font-weight: 600;">✓ Image selected</p>
                        </div>
                    </div>
                </div>
                <button type="submit" class="submit-btn">Add Event</button>
            </form>
        </div>
    </div>
</body>
