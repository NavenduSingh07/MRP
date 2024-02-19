<?php
include 'db_connect.php';

if(isset($_GET['table'])) {
    $table = $_GET['table'];

    // Fetch and display the table content based on the provided table name
    switch($table) {
        case 'inventory':
            fetchInventoryTable();
            break;
        case 'inventorypanels':
            fetchInventoryPanelsTable();
            break;
        case 'damage':
            fetchDamageTable();
            break;
        default:
            echo "Invalid table name";
    }
}

function fetchInventoryTable() {
    global $conn;
    $sql = "SELECT * FROM inventory";
    $result = $conn->query($sql);

    echo "<h2 class='text-center'>Raw Materials In Stock</h2>";

    if ($result->num_rows > 0) {
        echo "<table class='table table-bordered table-primary table-hover'>";
        echo "<thead><tr class='table-danger'><th scope='col'>S.No.</th><th scope='col'>Material Name</th><th scope='col'>UOM</th><th scope='col'>Quantity</th></tr></thead>";
        echo "<tbody>";
        $counter = 1;
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $counter . "</td>";
            echo "<td>" . $row["materialName"] . "</td>";
            echo "<td>" . $row["materialType"] . "</td>";
            echo "<td>" . $row["quantity"] . "</td>";
            echo "</tr>";
            $counter++;
        }
        echo "</tbody>";
        echo "</table>";
    } else {
        echo "<p>No data available</p>";
    }
}

function fetchInventoryPanelsTable() {
    global $conn;
    $sql = "SELECT * FROM inventorypanels";
    $result = $conn->query($sql);

    echo "<h2 class='text-center'>Panels In Stock</h2>";

    if ($result->num_rows > 0) {
        echo "<table class='table table-bordered table-primary table-hover'>";
        echo "<thead><tr class='table-danger'><th scope='col'>#</th><th scope='col'>Model Name</th><th scope='col'>Quantity</th></tr></thead>";
        echo "<tbody>";
        $counter = 1;
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $counter . "</td>";
            echo "<td>" . $row["ModelName"] . "</td>";
            echo "<td>" . $row["Quantity"] . "</td>";
            echo "</tr>";
            $counter++;
        }
        echo "</tbody>";
        echo "</table>";
    } else {
        echo "<p>No data available</p>";
    }
}

function fetchDamageTable() {
    global $conn;
    $sql = "SELECT * FROM damage";
    $result = $conn->query($sql);

    echo "<h2 class='text-center'>Record for Damages</h2>";

    if ($result->num_rows > 0) {
        echo "<table class='table table-bordered table-primary table-hover'>";
        echo "<thead><tr class='table-danger'><th scope='col'>#</th><th scope='col'>Material Name</th><th scope='col'>UOM</th><th scope='col'>Quantity</th><th scope='col'>Date Time</th></tr></thead>";
        echo "<tbody>";
        $counter = 1;
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $counter . "</td>";
            echo "<td>" . $row["materialNamew"] . "</td>";
            echo "<td>" . $row["materialTypew"] . "</td>";
            echo "<td>" . $row["quantity"] . "</td>";
            echo "<td>" . $row["timestamp"] . "</td>";
            echo "</tr>";
            $counter++;
        }
        echo "</tbody>";
        echo "</table>";
    } else {
        echo "<p>No data available</p>";
    }
}

$conn->close();
?>
