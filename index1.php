<?php include 'db.php'; ?>
<?php $page = $_GET['page'] ?? 'trainer'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Gym Management</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)), url(trainer.jpg);
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
            color: lightcoral;
            display: flex; 
            justify-content: flex-start; 
            height: 100vh; 
        }

        .menu {
            width: 150px;
            background: rgba(0, 0, 0, 0.7);
            padding: 30px;
            height: 100%;
            position: fixed;
            top: 0;
            left: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .menu a {
            display: block;
            margin: 15px 0;
            padding: 12px 30px;
            background: rgba(255, 255, 255, 0.2);
            color: lightcoral;
            text-decoration: none;
            font-size: 18px;
            border-radius: 10px;
            width: 100%;
            text-align: center;
            transition: background 0.3s;
        }

        .menu a:hover {
            background: rgba(255, 255, 255, 0.4);
        }

        .content {
            margin-left: 200px;
            padding: 30px;
            width: 70%;
            display: none; 
            flex-grow: 1;
        }

        .active {
            display: block; 
        }

        table {
            width: 100%; 
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #555;
            padding: 10px;
            text-align: center;
        }
        th {
            background: #222;
        }

    </style>
</head>
<body>


<!-- Left Menu -->
<div class="menu">
    <a href="?page=search">Search Trainer🔍</a>
    <a href="?page=trainer">Trainers List🏋</a>
    <a href="?page=schedule">Trainer Schedules📇</a>
</div>


<!-- Trainer Info -->
<div id="trainer" class="content <?php if ($page == 'trainer') echo 'active'; ?>">
    <h2>Trainer Information</h2>
    <?php
        $res = $conn->query("SELECT p.id,p.name,p.email,p.contact,t.speciality 
                             FROM person p INNER JOIN trainer t ON p.id=t.trainer_id");
        echo "<table><tr><th>ID</th><th>Name</th><th>Email</th><th>Contact</th><th>Speciality</th></tr>";
        while($row = $res->fetch_assoc()) {
            echo "<tr><td>{$row['id']}</td><td>{$row['name']}</td>
                  <td>{$row['email']}</td><td>{$row['contact']}</td><td>{$row['speciality']}</td></tr>";
        }
        echo "</table>";
    ?>
</div>


<!-- Trainer Schedules -->
<div id="schedule" class="content <?php if ($page == 'schedule') echo 'active'; ?>">
    <h2>Trainer Schedules</h2>
    <?php
        $res = $conn->query("SELECT p.id, p.name, s.day, s.time_slot 
                             FROM person p 
                             INNER JOIN trainer t 
                             INNER JOIN schedule s
                             ON t.trainer_id=p.id and t.trainer_id=s.trainer_id ");
        echo "<table><tr><th>ID</th><th>Trainer</th><th>Day</th><th>Time Slot</th></tr>";
        while($row = $res->fetch_assoc()) {
            echo "<tr><td>{$row['id']}</td><td>{$row['name']}</td>
                  <td>{$row['day']}</td><td>{$row['time_slot']}</td></tr>";
        }
        echo "</table>";
    ?>
</div>


<!-- Search Trainer -->
<div id="search" class="content <?php if ($page == 'search') echo 'active'; ?>">
    <h2>Search Trainer</h2>
    <form method="post" action="?page=search">
        <input type="text" name="keyword" placeholder="Enter trainer name or specialty..." 
            value="<?php if(isset($_POST['keyword'])) echo htmlspecialchars($_POST['keyword']); ?>" required>
        <button type="submit" name="search">Search</button>
    </form>

    <?php
    if (isset($_POST['search'])) {
        $keyword = $conn->real_escape_string($_POST['keyword']);

        /* ---------- Trainers Result ---------- */
        echo "<h3>Trainer Information</h3>";
        $res = $conn->query("SELECT p.id,p.name,p.email,p.contact,t.speciality 
                             FROM person p INNER JOIN trainer t JOIN schedule s 
                             ON t.trainer_id=p.id and t.trainer_id=s.trainer_id 
                             WHERE p.name LIKE '%$keyword%' 
                                OR t.speciality LIKE '%$keyword%' 
                                OR s.day LIKE '%$keyword%' 
                                OR s.time_slot LIKE '%$keyword%'");

        if ($res->num_rows > 0) {
            echo "<table><tr><th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Speciality</th></tr>";
            while($row = $res->fetch_assoc()) {
                echo "<tr><td>{$row['id']}</td><td>{$row['name']}</td>
                      <td>{$row['email']}</td><td>{$row['contact']}</td><td>{$row['speciality']}</td></tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No trainers found.</p>";
        }

        /* ---------- Schedules Result ---------- */
        echo "<h3>Trainer Schedule</h3>";
        $res = $conn->query("SELECT p.id, p.name, s.day, s.time_slot 
                             FROM person p  
                             JOIN trainer t JOIN schedule s 
                             ON t.trainer_id=p.id and t.trainer_id=s.trainer_id
                             WHERE p.name LIKE '%$keyword%' 
                                OR s.day LIKE '%$keyword%' 
                                OR s.time_slot LIKE '%$keyword%'
                                OR t.speciality LIKE '%$keyword%'");

        if ($res->num_rows > 0) {
            echo "<table><tr><th>ID</th><th>Trainer</th><th>Day</th><th>Time Slot</th></tr>";
            while($row = $res->fetch_assoc()) {
                echo "<tr><td>{$row['id']}</td><td>{$row['name']}</td>
                      <td>{$row['day']}</td><td>{$row['time_slot']}</td></tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No schedules found.</p>";
        }
    }
    ?>
</div>

</body>
</html>