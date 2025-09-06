<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: ../login_page/login.html");
    exit();
}
$servername='localhost';
$username='root';
$password='';
$dbname='Fitverse';
$conn= new mysqli($servername,$username,$password,$dbname);
if($conn->connect_error){
    die('Connection failed'. conn->connect_error);
}
// $member_id = $_SESSION['id'];
$height=$_POST['height'];
$weight=$_POST['weight'];
$fitness_goal=$_POST['fitness_goal'];
$injury=$_POST['injury_status'];
$nutration_type=$_POST['nutration_preference'];

$bmi=$weight / ($height * $height);
if ($bmi < 18.5) {
    $a=18.5;
} 
elseif ($bmi < 24.9) {
    $a=24.9;
} 
elseif ($bmi < 29.9) {
    $a=29.9;
}
elseif ($bmi < 39.9){
    $a=39.9;
}
else {
    $a=42;
}
$s1 = "SELECT * FROM fitness_plan WHERE bmi= $a AND fitness_goal = '$fitness_goal'";
$result = $conn->query($s1);
$plan = $result->fetch_assoc();

$x = "SELECT exercise FROM fitness_exercises WHERE bmi= $a AND fitness_goal = '$fitness_goal'";
$r1=$conn->query($x);
$ex=$r1->fetch_assoc();

$y = "SELECT suggestion FROM food_suggestion WHERE bmi= $a AND fitness_goal = '$fitness_goal' AND nutrition_type='$nutration_type'";
$r2=$conn->query($y);
$food=$r2->fetch_assoc();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Recommendation</title>
    <link rel="stylesheet" href="recommend.css">
</head>
<body>
    <div id="bar">
        <div id ="logo">
            <a href="../home_page/home.php">Fitverse</a>
        </div>
        <ul id="menu">
            <li><a href="../class.html">Class Booking</a></li>
            <li><a href="recommendation.html">Smart recommendation</a></li>
            <li><a href="../index.php">Playlist</a></li>
            <li><a href="../login_page/logout.php">Logout</a></li>
        </ul>
    </div>
    <div class="top">
        <div class="a">
            <h2>
                Your BMI Analysis: </br><?= round($bmi, 2) ?> <br/><?= $plan['status'] ?>
            </h2>
            <div class="b">
                <p><b>Fitness Goal:</b> <?= $fitness_goal ?></p>
                <p><b>Injury Status:</b> <?= $injury ?></p>
            </div>
        </div>
    </div>



    <div class="right">
        <div class="img">
            <img src="food2.jpg">
        </div>
        <div class="t-left">
            <h2>🥗 Nutrition Recommendations</h2>
            <p><b>Calories:</b> <?= $plan['calories'] ?> kcal</p>
            <p><b>Protein:</b> <?= $plan['protein'] ?> g</p>
            <p><b>Carbs:</b> <?= $plan['carbs'] ?> g</p>
            <p><b>Fat:</b> <?= $plan['fat'] ?> g</p>
        </div>

    </div>



    <div class="left">
        <div class="img">
            <img src="food.jpeg">
        </div>
        <div class="t-right">
            <?php
            echo "<h2>Nutrition suggestion for <u>$fitness_goal and $nutration_type</u>:</h2>";
            echo '<ul class="exercise-list">';
            while ($food=$r2->fetch_assoc()) {
                echo '<li>' . $food['suggestion'] . '</li>';
            }
            echo '</ul>';
            ?>
        </div>
    </div>



    <div class="right">
        <div class="img">
            <img src="workout_duration.png">
        </div>
        <div class="t-left">
            <h2>Duration & Frequency</h2>
            <p>Daily Duration: <?= $plan['workout_duration'] ?> minutes</p>
            <p>Weekly Frequency: <?= $plan['weekly_frequency'] ?> days</p>
            <p>Intensity: Low to moderate</p>
        </div>
    </div>



    <div class="left">
        <div class="img">
            <img src="rope.jpg">
        </div>
        <div class="t-right">
            <?php
            echo "<h2>Recommended Exercises for <u>$fitness_goal</u>:</h2>";
            echo '<ul class="exercise-list">';
            if ($injury == "None") {
                while ($row = $r1->fetch_assoc()) {
                    echo '<li>' . $row['exercise'] . '</li>';
                }
                echo "<p class='note'>💪 You're injury-free! Keep pushing toward your goal.</p>";
            } 
            else if ($injury == "Upper Body Injury") {
                echo '<li>Walking or Light Jogging</li>';
                echo '<li>Stationary Cycling</li>';
                echo '<li>Leg Press (low weight)</li>';
                echo '<li>Bodyweight Squats</li>';
                echo '<li>Glute Bridges</li>';
                echo "<p class='note'>💚 Focus on lower body strength while giving your upper body time to heal. Wishing you a strong recovery!</p>";
            } 
            else if ($injury == "Lower Body Injury") {
                echo '<li>Seated Shoulder Press (light)</li>';
                echo '<li>Bicep Curls</li>';
                echo '<li>Tricep Extensions</li>';
                echo '<li>Chest Press Machine</li>';
                echo '<li>Seated Row</li>';
                echo "<p class='note'>🌟 Take care of your legs! Focus on building your upper body while healing.</p>";
            } 
            else if ($injury == "Back/Spine Injury") {
                echo '<li>Gentle Stretching (cat-cow, child’s pose)</li>';
                echo '<li>Pelvic Tilts</li>';
                echo '<li>Bird Dogs</li>';
                echo '<li>Wall Sits (short duration)</li>';
                echo '<li>Light Resistance Band Work</li>';
                echo "<p class='note'>🧘 Stay gentle on your back—slow progress is still progress. Heal well!</p>";
            } 
            else if ($injury == "Other/Chronic Condition") {
                echo '<li>Light Yoga or Mobility Drills</li>';
                echo '<li>Breathing & Core Activation Exercises</li>';
                echo '<li>Stationary Cycling (low intensity)</li>';
                echo '<li>Resistance Band Workouts</li>';
                echo '<li>Swimming or Water Aerobics</li>';
                echo "<p class='note'>🌼 Gentle movement is the best medicine. Stay consistent and keep healing!</p>";
            }
            echo '</ul>';
            ?>
        </div>
    </div>



    <script>
    document.addEventListener("DOMContentLoaded", () => {
    const sections = document.querySelectorAll('.right, .left');

    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('in-view');
        }
        });
    }, { threshold: 0.2 });

    sections.forEach(section => {
        observer.observe(section);
    });
    });
    </script>
</body>
</html>