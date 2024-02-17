<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the values from the form
    $product = $_POST['product'];
    $productType = $_POST['productType'];
    $productQty = $_POST['productQty'];

    // Prepare and execute SQL query to insert data into damage_product table
    $sql1 = "INSERT INTO damage (materialNamew, materialTypew, quantity) VALUES (?, ?, ?)";
    $stmt1 = $conn->prepare($sql1);
    $stmt1->bind_param("ssi", $product, $productType, $productQty); // 'ssi' indicates string, string, integer
    if ($stmt1->execute() === false) {
        echo "Error inserting data into damage_product table: " . $stmt1->error;
    }

    // Prepare and execute SQL query to update inventory table
    $sql2 = "UPDATE inventory SET quantity = quantity - ? WHERE materialName = ?";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bind_param("is", $productQty, $product); // 'is' indicates integer, string
    if ($stmt2->execute() === false) {
        echo "Error updating inventory: " . $stmt2->error;
    }

    // Close prepared statements
    $stmt1->close();
    $stmt2->close();

    // Redirect back to the previous page or display success message
    header("Location: initiateBuild.php");
    // exit();
}
?>