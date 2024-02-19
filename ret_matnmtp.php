<?php
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
?>