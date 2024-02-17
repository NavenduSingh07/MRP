<?php include 'db_connect.php'; ?>

<!doctype html>
<html lang="en">

<head>
    <title>Dashboard</title>
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
        </nav><br><br>
    </header>

    <main>

        <div><a class="btn btn-danger" href="in.php" role="button">Add Raw Materials to Inventory</a></div><br>
        <div><a class="btn btn-danger" href="initiateBuild.php" role="button">Initiate Build</a></div>
            <br>
            <div class="container mt-5">
    <h2 class="text-center">Raw Materials In Stock</h2>
    <br>
    <table class="table table-bordered table-primary table-hover">
        <thead>
            <tr class="table-danger">
                <th scope="col">S.No.</th>
                <th scope="col">Material Name</th>
                <th scope="col">UOM</th>
                <th scope="col">Quantity</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Include your database connection file here
            include 'db_connect.php';

            // Query to fetch data from the inventory table
            $sql = "SELECT materialName, materialType, quantity FROM inventory";
            $result = $conn->query($sql);

            // Counter variable for serial number
            $counter = 1;

            if ($result->num_rows > 0) {
                // Output data of each row
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $counter . "</td>";
                    echo "<td>" . $row["materialName"] . "</td>";
                    echo "<td>" . $row["materialType"] . "</td>";
                    echo "<td>" . $row["quantity"] . "</td>";
                    echo "</tr>";
                    $counter++;
                }
            } else {
                echo "<tr><td colspan='4'>No data available</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>


        <div class="container mt-5">
            <h2 class="text-center">Panels In Stock</h2>
            <br>
            <table class="table table-bordered table-primary table-hover">
                <thead>
                    <tr class="table-danger">
                        <th scope="col">#</th>
                        <th scope="col">Model Name</th>
                        <th scope="col">Quantity</th>
                    </tr>
                </thead>
                <tbody>
            <?php
            // Include your database connection file here
            include 'db_connect.php';

            // Query to fetch data from the inventory table
            $sql = "SELECT ModelName, Quantity FROM inventorypanels";
            $result = $conn->query($sql);

            // Counter variable for serial number
            $counter = 1;

            if ($result->num_rows > 0) {
                // Output data of each row
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $counter . "</td>";
                    echo "<td>" . $row["ModelName"] . "</td>";
                    echo "<td>" . $row["Quantity"] . "</td>";
                    echo "</tr>";
                    $counter++;
                }
            } else {
                echo "<tr><td colspan='4'>No data available</td></tr>";
            }
            ?>
        </tbody>
            </table>
        </div>

        <div class="container mt-5">
            <h2 class="text-center">Record for Damages</h2>
            <br>
            <table class="table table-bordered table-primary table-hover">
                <thead>
                    <tr class="table-danger">
                        <th scope="col">#</th>
                        <th scope="col">Material Name</th>
                        <th scope="col">UOM</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Date Time</th>
                    </tr>
                </thead>
                <tbody>
            <?php
            // Include your database connection file here
            include 'db_connect.php';

            // Query to fetch data from the inventory table
            $sql = "SELECT materialNamew, materialTypew, quantity, timestamp FROM damage";
            $result = $conn->query($sql);

            // Counter variable for serial number
            $counter = 1;

            if ($result->num_rows > 0) {
                // Output data of each row
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
            } else {
                echo "<tr><td colspan='4'>No data available</td></tr>";
            }
            ?>
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
</body>

</html>