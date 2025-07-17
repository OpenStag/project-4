<?php
if (isset($_GET['id'])) {
    $conn = new mysqli("localhost", "root", "", "event_database");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $id = intval($_GET['id']);
    $sql = "DELETE FROM event WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        header("Location: index2.php");
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }

    $conn->close();
}
?>
