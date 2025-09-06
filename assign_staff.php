<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $equipment_id = $_POST['equipment_id'] ;
    $staff_id = $_POST['staff_id'];

    if ($equipment_id && $staff_id) {
        $sql = "UPDATE equipment 
                SET  status='Assigned' 
                WHERE equipment_id='$equipment_id' AND status='Need maintenance'";
        $sql2 = "UPDATE maintenance 
                SET status='Assigned', staff_id='$staff_id' 
                WHERE equipment_id='$equipment_id' AND status='Need maintenance'";
         if (($conn->query($sql))  && $conn->query($sql2)) { 
            header("Location: index2.php");
            exit;
        } else {
            die("Query failed: " . $conn->error);
        }
    } else {
        die("Error: Equipment or Staff ID missing");
    }
}
?>
