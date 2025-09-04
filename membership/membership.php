<?php
session_start();
$servername='localhost';
$username='root';
$password='';
$dbname='Fitverse';
$conn= new mysqli($servername,$username,$password,$dbname);
if($conn->connect_error){
    die('Connection failed'. $conn->connect_error);
}
$a = $conn->query("SELECT MAX(CAST(SUBSTRING(id, 3) AS UNSIGNED)) AS max_num FROM membership");
$row = $a->fetch_assoc();
if ($row['max_num']) {
    $new_num = $row['max_num'] + 1;
    $new_id = "mb" . $new_num;
    $plan_id="p".$new_num;
}
else {
    $new_id = "mb001";
    $plan_id='p001';
}
$_SESSION['id']=$new_id;
$_SESSION['plan_name']=$_POST['name'];
$_SESSION['price']= $_POST['price'];
$_SESSION['duration']= $_POST['duration'];
$_SESSION['plan_id']= $plan_id;
header("Location: signup.html");
?>