<?php include 'db_connect.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Dashboard</title>
    <link rel="icon" href="images/title.png" type="image/icon type">
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
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
    <br><br>
</header>

<main class="container-fluid">
    <div class="row mt-3">
        <!-- Sidebar -->
        <div class="col-md-3">
            <div class="bg-light p-3">
                <h4>Menu</h4>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="in.php">Add Raw Materials to Inventory</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="initiateBuild.php">Initiate Build</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Orders</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Inventory</a>
                    </li>
                    <!-- Add more menu items as needed -->
                </ul>
            </div>
        </div>
        <!-- Main Content -->
        <div class="col-md-9">
            <h2>MRP Software Dashboard</h2>
            <!-- Add your dashboard content here -->

            <!-- Raw Materials In Stock Card -->
            <div class="row">
                <div class="col-md-4 mb-3">
                    <div class="card bg-warning bg-gradient">
                        <div class="card-body">
                            <h5 class="card-title">Raw Materials In Stock</h5>
                            <p class="card-text">
                                <?php
                                $sql = "SELECT COUNT(*) AS total FROM inventory";
                                $result = $conn->query($sql);
                                $row = $result->fetch_assoc();
                                echo "Total Raw Materials: " . $row['total'];
                                ?>
                            </p>
                            <button data-table="inventory" class="toggleTable btn btn-danger">View Details</button>
                        </div>
                    </div>
                </div>
                <!-- Panels In Stock Card -->
                <div class="col-md-4 mb-3">
                    <div class="card bg-warning bg-gradient">
                        <div class="card-body">
                            <h5 class="card-title">Panels In Stock</h5>
                            <p class="card-text">
                                <?php
                                $sql = "SELECT COUNT(*) AS total FROM inventorypanels";
                                $result = $conn->query($sql);
                                $row = $result->fetch_assoc();
                                echo "Total Panels In Stock: " . $row['total'];
                                ?>
                            </p>
                            <button data-table="inventorypanels" class="toggleTable btn btn-danger">View Details</button>
                        </div>
                    </div>
                </div>
                <!-- Record for Damages Card -->
                <div class="col-md-4 mb-3">
                    <div class="card bg-warning bg-gradient">
                        <div class="card-body">
                            <h5 class="card-title">Record for Damages</h5>
                            <p class="card-text">
                                <?php
                                $sql = "SELECT COUNT(*) AS total FROM damage";
                                $result = $conn->query($sql);
                                $row = $result->fetch_assoc();
                                echo "Total: " . $row['total'];
                                ?>
                            </p>
                            <button data-table="damage" class="toggleTable btn btn-danger">View Details</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
     <!-- Container for Displaying Tables -->
     <div class="col-md-9 offset-md-3 mt-5">
        <div id="tablesDisplayContainer">
            <!-- Table Content Will Be Displayed Here -->
        </div>
    </div> 
</main>
    <!-- Bootstrap JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
        crossorigin="anonymous"></script>
    <script>
        // Function to toggle visibility of table
        document.querySelectorAll('.toggleTable').forEach(button => {
            button.addEventListener('click', function () {
                var tableContainer = document.getElementById('tablesDisplayContainer');
                var tableName = this.getAttribute('data-table');
                if (tableContainer.style.display === 'none') {
                    tableContainer.style.display = 'block';
                    fetchAndDisplayTable(tableName, '#tablesDisplayContainer');
                    this.innerText = 'Hide Details';
                } else {
                    tableContainer.style.display = 'none';
                    this.innerText = 'View Details';
                }
            });
        });

        // Function to fetch and display table
        function fetchAndDisplayTable(tableName, container) {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        document.querySelector(container).innerHTML = xhr.responseText;
                    } else {
                        console.error('Error fetching data:', xhr.status, xhr.statusText);
                    }
                }
            };
            xhr.open('GET', 'fetch_table.php?table=' + tableName, true);
            xhr.send();
        }
    </script>
</body>

</html>
