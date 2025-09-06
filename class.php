<?php
$servername = "localhost"; 
$username   = "root";      
$password   = "";          
$dbname     = "fitverse";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$date= $_POST['date'];
$class_type= $_POST['class_type'];
$time = $_POST['time'];
$workout_type = $_POST['workout_type'];
$result = $conn->query("SELECT class_id FROM class ORDER BY class_id DESC LIMIT 1");
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $last_id = $row['class_id'];
    $num = intval(substr($last_id, 1)) + 1;
    $new_id = "C" . str_pad($num, 2, "0", STR_PAD_LEFT);
} else {
    $new_id = "C01";
}
$capacity = 20;  
$sql = "INSERT INTO class (class_id, capacity, time, date, class_type, workout_time, session_focus)
        VALUES ('$new_id', '$capacity', '$time', '$date', '$class_type', '$workout_type', '$workout_type')";
if ($conn->query($sql) === TRUE) {
    echo "
    <html>
    <head>
        <title>Booking Successful</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background: url('success_bg.jpg') no-repeat center center fixed;
                background-size: cover;
                color: #fff;
                text-align: center;
                padding: 80px;
            }
            .card {
                background: rgba(0, 0, 0, 0.6);
                padding: 30px;
                border-radius: 12px;
                display: inline-block;
                box-shadow: 0 4px 12px rgba(0,0,0,0.5);
            }
            h2 {
                color: #00ff88;
            }
            p {
                font-size: 18px;
                margin: 8px 0;
            }
            a {
                display: inline-block;
                margin-top: 20px;
                padding: 10px 20px;
                background: #00ff88;
                color: black;
                text-decoration: none;
                border-radius: 6px;
                font-weight: bold;
            }
            a:hover {
                background: #00cc66;
            }
        </style>
    </head>
    <body>
        <div class='card'>
            <h2>✅ Booking Successful!</h2>
            <p><b>Class ID:</b> $new_id</p>
            <p><b>Class Type:</b> $class_type</p>
            <p><b>Workout Time:</b> $workout_type</p>
            <p><b>Session Focus:</b> $workout_type</p>
            <a href='class.html'>Book Another Class</a>
        </div>
    </body>
    </html>
    ";
} else {
    echo "❌ Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
