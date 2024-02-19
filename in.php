<?php
include 'db_connect.php';

// Function to retrieve raw material names from database
function getRawMaterialNames($conn)
{
    $sql = "SELECT `materialName` FROM `raw_material`";
    $result = $conn->query($sql);
    $raw_materials = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $raw_materials[] = $row["materialName"];
        }
    }

    return $raw_materials;
}

// Function to retrieve raw material types from database
function getRawMaterialTypes($conn)
{
    $sql = "SELECT `materialType` FROM `uom`";
    $result = $conn->query($sql);
    $raw_material_types = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $raw_material_types[] = $row["materialType"];
        }
    }

    return $raw_material_types;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $material_name = $_POST['material_name'];
    $material_type = $_POST['material_type'];
    $quantity = $_POST['quantity'];

    $sql = "SELECT quantity FROM inventory WHERE materialName = '$material_name'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Fetch the quantity value
        $row = $result->fetch_assoc();
        $newquantity = $row["quantity"] + $quantity;
    } else {
        $newquantity = $quantity; // Initialize new quantity if material not found in inventory
    }

    // Insert data into inventory table
    $sql1 = "UPDATE inventory SET quantity = '$newquantity' WHERE materialName = '$material_name'";
    $sql2 = "INSERT INTO `raw_materialadded` (materialNamea, materialTypea, quantity) VALUES ('$material_name', '$material_type', '$quantity')";

    // Begin a transaction
    $conn->begin_transaction();

    // Execute both queries
    if ($conn->query($sql1) === TRUE && $conn->query($sql2) === TRUE) {
        // If both queries are successful, commit the transaction
        $conn->commit();
        // Display success message with JavaScript timeout
        echo "<div id='success-message' class='alert alert-success' role='alert'>Records added successfully</div>";
        echo "<script>
                document.addEventListener('DOMContentLoaded', function () {
                    setTimeout(function() {
                        document.getElementById('success-message').style.display = 'none';
                    }, 3000); // Set the timeout duration in milliseconds (3 seconds in this case)
                });
              </script>";
    } else {
        // If any query fails, rollback the transaction
        $conn->rollback();
        echo "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Add Raw Material to Inventory</title>
    <link rel="icon" href="images/title.png" type="image/icon type">


    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Bootstrap CSS v5.3.2 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>

    <header>
        <?php
        include 'nav.php'; ?>

    </header>

    <main class="container mt-4">
        <h2 class="text-center">Add Raw Material to Inventory</h2>

        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="mt-4">
            <div class="mb-3">
                <label for="material_name" class="form-label">Raw Material Name:</label>
                <select name="material_name" id="material_name" class="form-select">
                    <?php
                    $raw_material_names = getRawMaterialNames($conn);
                    foreach ($raw_material_names as $name) {
                        echo "<option value='$name'>$name</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="material_type" class="form-label">Raw Material Type:</label>
                <select name="material_type" id="material_type" class="form-select">
                    <?php
                    $raw_material_types = getRawMaterialTypes($conn);
                    foreach ($raw_material_types as $type) {
                        echo "<option value='$type'>$type</option>";
                    }
                    ?>
                </select>
            </div>


            <div class="mb-3">
                <label for="quantity" class="form-label">Quantity:</label>
                <input type="number" name="quantity" id="quantity" class="form-control" min="0.1" step="0.01" required>
            </div>

            <button type="submit" class="btn btn-danger">Add to Inventory</button>
        </form>

        <div class="mt-4 text-center">
            <h3>Added Raw Material</h3>
            <br><br>


            <?php
            $sql = "SELECT * FROM raw_materialadded ORDER BY timestamp DESC LIMIT 10";
            $result = $conn->query($sql);

            // Check if there are rows returned
            if ($result->num_rows > 0) {
                // Initialize a counter
                $counter = 1;

                // Output data of each row
                echo "<table class='table table-bordered table-primary table-hover'><thead><tr class='table-danger'><th>SNo.</th><th>Material Name</th><th>Material Type</th><th>Quantity</th><th>Date&Time</th></tr></thead><tbody>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr><td>" . $counter . "</td><td>" . $row["materialNamea"] . "</td><td>" . $row["materialTypea"] . "</td><td>" . $row["quantity"] . "</td><td>" . $row["timestamp"] . "</td></tr>";
                    $counter++; // Increment the counter
                }
                echo "</tbody></table>";
            } else {
                echo "0 results";
            }
            ?>


    </main>

    <footer>
        <!-- Your footer content -->
    </footer>


    <!-- Bootstrap JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
        crossorigin="anonymous"></script>
</body>

</html>