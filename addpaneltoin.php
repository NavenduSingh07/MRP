<?php
// Include your database connection file here
include 'db_connect.php';

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['triggerButton'])) {
    // Retrieve data from the form
    $modelName = $_POST['modelName'];
    $quantity = $_POST['quantity'];
    $timestamp = $_POST['timestamp'];

    // First query: Update inventorypanels table
    $sql1 = "UPDATE inventorypanels SET Quantity = Quantity + $quantity WHERE ModelName = '$modelName'";
    if ($conn->query($sql1) === FALSE) {
        echo "Error updating inventorypanels: " . $conn->error;
    }

    // Second query: Insert data into panel_stock table
    $sql2 = "INSERT INTO panel_stock (ModelName, Quantity, statusID) VALUES ('$modelName', $quantity, 2)";
    if ($conn->query($sql2) === FALSE) {
        echo "Error inserting into panel_stock: " . $conn->error;
    }

    // Third query: Remove data from panel_stock table
    $sql3 = "DELETE FROM panel_stock WHERE ModelName = '$modelName' AND Quantity = $quantity AND timestamp = '$timestamp' AND statusID = 1";
    if ($conn->query($sql3) === FALSE) {
        echo "Error deleting from panel_stock: " . $conn->error;
    }

    header("Location: initiateBuild.php");
}
?>
