<?php
include 'db_connect.php';

// Function to retrieve raw material names from database
function getRawMaterialNames($conn) {
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
function getRawMaterialTypes($conn) {
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

    // Insert data into inventory table
    $sql = "INSERT INTO inventory (materialName, materialType, quantity) VALUES ('$material_name', '$material_type', $quantity)";
    
    if ($conn->query($sql) === TRUE) {
        echo "Record added successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!doctype html>
<html lang="en">
    <head>
        <title>Add Raw Material to Inventory</title>
        <!-- Required meta tags -->
        <meta charset="utf-8" />
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1, shrink-to-fit=no"
        />

        <!-- Bootstrap CSS v5.2.1 -->
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
            crossorigin="anonymous"
        />
    </head>

    <body>
        <header>
            <!-- place navbar here -->
        </header>
        <main>
        <h2>Add Raw Material to Inventory</h2>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <label for="material_name">Raw Material Name:</label>
    <select name="material_name" id="material_name">
        <?php
            $raw_material_names = getRawMaterialNames($conn);
            foreach ($raw_material_names as $name) {
                echo "<option value='$name'>$name</option>";
            }
        ?>
    </select><br><br>

    <label for="material_type">Raw Material Type:</label>
   <select name="material_type" id="material_type">
     <?php
            $raw_material_types = getRawMaterialTypes($conn);
            foreach ($raw_material_types as $type) {
                echo "<option value='$type'>$type</option>";
            }
        ?> 
    </select><br><br>  

    <label for="quantity">Quantity:</label>
    <input type="number" name="quantity" id="quantity" min="1.0" step="0.1"><br><br>

    <input type="submit" value="Add to Inventory">
</form>
        </main>
        <footer>
            <!-- place footer here -->
        </footer>
        <!-- Bootstrap JavaScript Libraries -->
        <script
            src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
            integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
            crossorigin="anonymous"
        ></script>

        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
            integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
            crossorigin="anonymous"
        ></script>
    </body>
</html>

