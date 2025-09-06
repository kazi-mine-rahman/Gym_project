<?php
include 'db.php';

if(isset($_POST['add_equipment'])) {
    $name = ($_POST['name']);
    $purchasing_date = ($_POST['purchasing_date']);
    $status =($_POST['status']);

    $sql = "INSERT INTO equipment (name, purchasing_date, status) 
            VALUES ('$name', '$purchasing_date', '$status')";

    if($conn->query($sql)) {
        header("Location: index2.php");
        exit();
    } else {
        die("Insert failed: " . $conn->error);
    }
}
?>