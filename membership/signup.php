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
$name=$_POST['name'];
$email= $_POST['email'];
$password= $_POST['password'];
$contact= $_POST['contact'];
$location=$_POST['location'];
$gender= $_POST['gender'];
$birthdate=$_POST['birthdate'];
$height= $_POST['height'];
$weight=$_POST['weight'];
$fitness= $_POST['fitness_goal'];
$nutrition=$_POST['nutration_preference'];
$injury=$_POST['injury_status'];
$new_id=$_SESSION['id'];
$plan_id=$_SESSION['plan_name'];
$price=$_SESSION['price'];
$duration=$_SESSION['duration'];
$plan_id=$_SESSION['plan_id'];
$sql = "INSERT INTO person (id,gender,contact,email,birthdate, name,location, password)
VALUES ('$new_id','$gender','$contact','$email','$birthdate','$name','$location','$password')";
$result1= mysqli_query($conn, $sql);
$sql2= "INSERT INTO member (member_id, height_m, weight_kg, fitness_goal, injury_history, nutration_preference) VALUES ('$new_id','$height','$weight','$fitness','$injury','$nutrition')";
$result2=mysqli_query($conn,$sql2);
$sql3 = "INSERT INTO membership (plan_id, id,plan_name,duration,price) VALUES ('$plan_id','$new_id','$name','$duration','$price')";
$result3= mysqli_query($conn, $sql3);
if ($result1 && $result2 && $result3){
    echo "<script>alert('Signup successful! Welcome to Fitverse. Your member id is $new_id'); window.location.href='../login_page/login.html';</script>";
} 
else{
    echo "Error while inserting ". $conn->error;
}
?>