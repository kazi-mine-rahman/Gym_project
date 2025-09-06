<?php
include 'db.php';

if(isset($_POST['add_maintenance'])) {
    $type =($_POST['maintenance_type']);
    $cost =($_POST['cost']);
    $date =($_POST['maintenance_date']);
    $status = ($_POST['status']);
    $equipment_id = ($_POST['equipment_id']);

    $sql = "INSERT INTO maintenance (maintenance_type, cost, maintenance_date, status, equipment_id, staff_id) 
            VALUES ('$type', '$cost', '$date', '$status', '$equipment_id', NULL)";

    if($conn->query($sql)) {
        $update_sql = "UPDATE equipment SET status='Need maintenance' 
        WHERE equipment_id='$equipment_id'";
        $conn->query($update_sql);

        header("Location: index2.php");
        exit();
    } else {
        die("Insert failed: " . $conn->error);
    }
}
?>
