<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: first_page.html");
    exit();
}
$role = $_SESSION['role'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fitverse</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div id="bar">
        <div id ="logo">
            <h2>Fitverse</h2>
        </div>
        <ul id="menu">
            <?php if ($role!="member") { ?>
                <li><a href="../index2.php">Maintenance</a></li>
            <?php } ?>
            <li><a href="../index1.php">Trainer</a></li>
            <li><a href="../class.html">Class Booking</a></li>
            <li><a href="../smart_recommendation/reco_input.html">Smart recommendation</a></li>
            <li><a href="../index.php">Playlist</a></li>
            <li><a href="../login_page/logout.php">Logout</a></li>
        </ul>
    </div>
    <section id="back">
        <video autoplay loop muted playsinline class="video_b">
            <source src="gym_video.mp4" type="video/mp4">
        </video>
        <div id="speach">
            <h1>
                Unlock your best self at Fitverse.<br>
            </h1>
            <p>
                No matter your goal—strength, endurance, or overall fitness<br>our trainers make the journey simple and motivating.
            </p>
        </div>
    </section>
</body>
</html>