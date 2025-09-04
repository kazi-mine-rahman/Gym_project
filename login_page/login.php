<?php
session_start();
$servername='localhost';
$username='root';
$password='';
$dbname='Fitverse';
$conn= new mysqli($servername,$username,$password,$dbname);
if($conn->connect_error){
    die('Connection failed'. conn->connect_error);
}
if ($_SERVER["REQUEST_METHOD"]== "POST") {
    $id = $_POST['id'];
    $pass = $_POST['password'];
    $sql = "SELECT * FROM person WHERE ID='$id' AND password='$pass'";
    $result = $conn->query($sql);
    if ($result->num_rows==1){
        $_SESSION['id']=$id;
        if (str_starts_with($id, "mb")) {
            $_SESSION['role']="member";
        } 
        elseif (str_starts_with($id, "tr")) {
            $_SESSION['role']="trainer";
        } 
        elseif (str_starts_with($id, "sf")) {
            $_SESSION['role']="staff";
        }
        header("Location: ../home_page/home.php");
        exit();
    }
    else{
        echo "<script>alert('Invalid ID or Password. Please try again'); window.location.href='login.html';</script>";
        // echo "<script>alert('Invalid ID or Password');</script>";
    }
}
?>