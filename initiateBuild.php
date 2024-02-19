<?php
include 'db_connect.php';
include 'ret_matnmtp.php';
// Function to retrieve model names from database
function getModelNames($conn)
{
    $sql = "SELECT `Model_Name` FROM `panelmodel`";
    $result = $conn->query($sql);
    $model_names = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $model_names[] = $row["Model_Name"];
        }
    }

    return $model_names;
}

// Function to retrieve model ID based on model name
function getModelID($conn, $model_name)
{
    $sql = "SELECT `id` FROM `panelmodel` WHERE `Model_Name` = '$model_name'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row["id"];
    } else {
        return null;
    }
}

// Function to retrieve material names and quantities for a given model ID
function getReqMaterials($conn, $model_id)
{
    $sql = "SELECT `materialName`, `reqMaterial` FROM `req_material` WHERE `id` = '$model_id'";
    $result = $conn->query($sql);
    $req_materials = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $req_materials[] = array(
                'materialName' => $row['materialName'],
                'reqMaterial' => $row['reqMaterial']
            );
        }
    }

    return $req_materials;
}

// Function to fetch panels in process from panel_stock table
function getPanelsInProcess($conn)
{
    $sql = "SELECT `ModelName`, `Quantity`, `CName`, `timestamp` FROM `panel_stock` WHERE `statusID` = 1";
    $result = $conn->query($sql);
    $panels_in_process = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $panels_in_process[] = $row;
        }
    }

    return $panels_in_process;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the values from the form
    $model_name = $_POST['Model_Name'];
    $CName = $_POST['CName'];    
    $quantity = $_POST['quantity'];

    // Retrieve the ID of the selected model
    $model_id = getModelID($conn, $model_name);

    if ($model_id !== null) {

        // Retrieve required materials for the selected model
        $req_materials = getReqMaterials($conn, $model_id);
        if (!empty($req_materials)) {
            foreach ($req_materials as $material) {
                $materialName = $material['materialName'];
                $requiredQuantity = $quantity * $material['reqMaterial'];
                // Subtract required quantity from inventory
                $sql3 = "UPDATE inventory SET quantity = quantity - $requiredQuantity WHERE materialName = '$materialName'";
                if ($conn->query($sql3) === false) {
                    echo "Error updating inventory: " . $conn->error;
                }
            }
            // Insert into panel_stock after the loop
            $sql4 = "INSERT INTO `panel_stock` (`ModelName`, `CName`,`Quantity`, `statusID`) VALUES ('$model_name', '$CName', '$quantity', '1')";
            if ($conn->query($sql4) === false) {
                echo "Error inserting into panel_stock: " . $conn->error;
            }
        } else {
            echo "No required materials found for this model.";
        }
    } else {
        echo "Model ID not found";
    }

    // Additional code for initiating build can be added here
}



// Fetch panels in process
$panels_in_process = getPanelsInProcess($conn);
?>


<!doctype html>
<html lang="en">

<head>
    <title>Initiate Build</title>
    <link rel="icon" href="images/title.png" type="image/icon type">

    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-danger">
            <div class="container-fluid">
                <a class="navbar-brand" href="testdashboard.php">
                    <!-- Add your logo image here -->
                    <img src="images/logo.png" alt="Your Logo" height="40" width="auto">
                </a>
            </div>
        </nav>
    </header>

    <main class="container mt-4">
        <h2 class="text-center">Choose Panel Type to Initiate Build</h2>

        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="mb-3">
                <label for="Model_Name" class="form-label">Panel Model:</label>
                <select name="Model_Name" id="Model_Name" class="form-select" required>
                    <?php
                    $model_names = getModelNames($conn);
                    foreach ($model_names as $mname) {
                        echo "<option value='$mname'>$mname</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
        <label for="CName" class="form-label">Client Name:</label>
        <input type="text" name="CName" id="CName" class="form-control" required>
    </div>
            <div class="mb-3">
                <label for="quantity" class="form-label">Quantity:</label>
                <input type="number" name="quantity" id="quantity" class="form-control" min="1" required>
            </div>

            <button type="submit" class="btn btn-danger">Initiate Build</button>
        </form>


        <!-- bootstrap table-->
        <div class="container mt-5">
            <h2 class="text-center">Panels In Process</h2>
            <br>
            <table class="table table-bordered table-primary">
                <thead>
                    <tr class='table-danger'>
                        <th>SNo</th>
                        <th>Model Name</th>
                        <th>Client Name</th>
                        <th>Quantity</th>
                        <th>Date Time</th>
                        <th>Record Damages</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Reverse the array to show newly added entries on top
                    $panels_in_process = array_reverse($panels_in_process);

                    // Loop through all entries
                    foreach ($panels_in_process as $index => $panel):
                        ?>
                        <tr>
                            <td>
                                <?php echo $index + 1; ?>
                            </td>
                            <td>
                                <?php echo $panel['ModelName']; ?>
                            </td>
                            <td>
                                <?php echo $panel['CName']; ?>
                            </td>
                            <td>
                                <?php echo $panel['Quantity']; ?>
                            </td>
                            <td>
                                <?php echo $panel['timestamp']; ?>
                            </td>
                            <td>
                                <div class="d-flex flex-row mb-3">
                                    <div>
                                        <button type="button" class="btn btn-danger" data-toggle="modal"
                                            data-target="#productModal">Add Materials</button>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <form method="post" action="addpaneltoin.php">
                                        <!-- Hidden input fields to store data for the selected row -->
                                        <input type="hidden" name="modelName" value="<?php echo $panel['ModelName']; ?>">
                                        <input type="hidden" name="CName" value="<?php echo $panel['CName']; ?>">
                                        <input type="hidden" name="quantity" value="<?php echo $panel['Quantity']; ?>">
                                        <input type="hidden" name="timestamp" value="<?php echo $panel['timestamp']; ?>">
                                        <!-- Button to trigger the form submission -->
                                        <button type="submit" class="btn btn-warning" name="triggerButton">In
                                            Process</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- end bootstrap table -->
        <!-- Add modal -->

        <div class="modal fade" id="productModal" tabindex="-1" role="dialog" aria-labelledby="productModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-center w-100" id="productModalLabel">Waste Material</h5>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="productForm" action="process_form.php" method="POST">
                            <div class="form-group">
                                <label for="product">Raw Material Wasted:</label>
                                <select class="form-control" id="product" name="product" required>
                                    <?php
                                    $raw_material_names = getRawMaterialNames($conn);
                                    foreach ($raw_material_names as $name) {
                                        echo "<option value='$name'>$name</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="productType">Product Type:</label>
                                <select class="form-control" id="productType" name="productType" required>
                                    <?php
                                    $raw_material_types = getRawMaterialTypes($conn);
                                    foreach ($raw_material_types as $type) {
                                        echo "<option value='$type'>$type</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="productQty">Quantity:</label>
                                <input type="number" class="form-control" id="productQty" name="productQty" min="0.1"
                                    step="0.01" required>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-success" id="submitBtn">Record</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- ends here -->

        <?php
        // Function to fetch panels manufactured from panel_stock table
        function getPanelsInStock($conn)
        {
            $sql = "SELECT `SNo`, `ModelName`, `CName`, `Quantity`, `timestamp` FROM `panel_stock` WHERE `statusID` = 2";
            $result = $conn->query($sql);
            $panels_in_stock = array();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $panels_in_stock[] = $row;
                }
            }

            return $panels_in_stock;
        }

        // Fetch panels Manufactured
        $panels_in_stock = getPanelsInStock($conn);
        ?>

        <h2 class="text-center">Panels Manufactured</h2>
        <br>
        <table class="table table-bordered table-primary">
            <thead>
                <tr class='table-danger'>
                    <th>SNo</th>
                    <th>Model Name</th>
                    <th>Client Name</th>
                    <th>Quantity</th>
                    <th> Date Time</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Reverse the array to display newly added items on top
                $panels_in_stock = array_reverse($panels_in_stock);
                // Slice the array to get the first 10 items
                $latest_panels = array_slice($panels_in_stock, 0, 10);
                foreach ($latest_panels as $index => $panel): ?>
                    <tr>
                        <td>
                            <?php echo $index + 1; ?>
                        </td>
                        <td>
                            <?php echo $panel['ModelName']; ?>
                        </td>
                        <td>
                            <?php echo $panel['CName']; ?>
                        </td>
                        <td>
                            <?php echo $panel['Quantity']; ?>
                        </td>
                        <td>
                            <?php echo $panel['timestamp']; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    </main>
    <footer>
        <!-- place footer here -->
    </footer>
    <!-- Bootstrap JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
        crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>
