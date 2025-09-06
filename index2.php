<?php 
include 'db.php';
$page = isset($_GET['page']) ? $_GET['page'] : 'all_equipment';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Equipment Management</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url(equipment.jpg);
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
            color: white;
            display: flex; 
            justify-content: flex-start;
            height: 100vh; 
        }
        .menu {
            width: 250px;
            background: rgba(0,0,0,0.7);
            padding: 30px 10px;
            height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            position: fixed;
            right: 0;
            top: 0;
        }
        .menu a {
            display: block;
            margin: 15px 0;
            padding: 10px;
            background: rgba(255,255,255,0.2);
            color: white;
            text-decoration: none;
            width: 90%;
            text-align: center;
            border-radius: 8px;
            transition: 0.3s;
        }
        .menu a:hover {
            background: rgba(255,255,255,0.4);
        }
        .content {
            flex-grow: 1;
            padding: 30px;
            display: none;
        }
        .active {
            display: block;
        }
        table {
            width: 80%;
            border-collapse: collapse;
            margin-top: 20px;
            color: white;
        }
        table, th, td {
            border: 1px solid #555;
            padding: 10px;
            text-align: center;
        }
        th {
            background: #222;
        }
        form input, form select, form button {
            padding: 8px;
            margin: 5px 0;
        }
        
        .active.form-section {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
            text-align: left;
            color: white;
        }

        .active.form-section form {
            width: 350px;
            background: rgba(0, 0, 0, 0.6);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 15px rgba(0,0,0,0.5);
        }

        .form-section input,
        .form-section select,
        .form-section button {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: none;
            border-radius: 5px;
        }

        .active.form-section button {
            background: #416792ff;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }

        .form-section button:hover {
            background: #3f79c5ff;
        }
    </style>
</head>
<body>

    <!-- Sidebar Menu -->
    <div class="menu">
        <a href="?page=all_equipment">All Equipment</a>
        <a href="?page=add_equipment">Add Equipment</a>
        <a href="?page=maintenance">Maintenance</a>
        <a href="?page=add_maintenance">Add to Maintenance</a>
        <a href="?page=assign_staff">Assign Staff</a>
    </div>

    
    <!-- All Equipment  -->
    <div id="all_equipment" class="content <?php if($page=='all_equipment') echo 'active'; ?>">
        <h2>All Gym Equipment</h2>
        <?php
        $res = $conn->query("SELECT name, COUNT(*) AS total_count, 
            MIN(purchasing_date) AS purchasing_date FROM equipment
            GROUP BY name ORDER BY name ASC");
        if ($res->num_rows > 0) {
            echo "<table><tr><th>Name</th><th>Purchasing Date</th><th>Total Count</th></tr>";
            while($row = $res->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['name']}</td>
                        <td>{$row['purchasing_date']}</td>
                        <td>{$row['total_count']}</td>
                      </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No equipment found.</p>";
        }
        ?>
    </div>


    <!-- Maintenance  -->
    <div id="maintenance" class="content <?php if($page=='maintenance') echo 'active'; ?>">
        <h2>Equipment Needing Maintenance</h2>
        <?php
        $res = $conn->query("SELECT * FROM equipment WHERE status='Need maintenance'");
        if ($res->num_rows > 0) {
            echo "<table>
                    <tr><th>ID</th><th>Name</th><th>Status</th></tr>";
            while($row = $res->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['equipment_id']}</td>
                        <td>{$row['name']}</td>
                        <td>{$row['status']}</td>
                      </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No equipment currently needs maintenance.</p>";
        }
        ?>
    </div>


    <!--Assign Staff -->
    <div id="assign_staff" class="content <?php if($page=='assign_staff') echo 'active'; ?>">
        <h2>Assign Staff for Maintenance</h2>
        <?php
        $res = $conn->query("SELECT * FROM equipment WHERE status='Need maintenance'");
        if ($res->num_rows > 0) {
            echo "<table>
                    <tr><th>ID</th><th>Name</th><th>Status</th><th>Assign Staff</th></tr>";
            while($row = $res->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['equipment_id']}</td>
                        <td>{$row['name']}</td>
                        <td>{$row['status']}</td>
                        <td>
                            <form method='post' action='assign_staff.php'>
                                <input type='hidden' name='equipment_id' value='{$row['equipment_id']}'>
                                <select name='staff_id' required>
                                    <option value=''>Select Staff</option>";
                
                $staff_res = $conn->query("SELECT p.id,p.name,s.role FROM person p INNER JOIN staffs s
                    ON p.id=s.staff_id");
                while($s = $staff_res->fetch_assoc()) { 
                    $nameWithRole = htmlspecialchars($s['name'] . " (" . $s['role'] . ")");
                    echo "<option value='{$s['id']}'>{$nameWithRole}</option>";
                }
                
                echo "          </select>
                                <button type='submit'>Assign</button>
                            </form>
                        </td>
                      </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No equipment currently needs maintenance.</p>";
        }
        ?>
    </div>

    <!-- Add New Equipment -->
    <div id="add_equipment" class="content form-section <?php if($page=='add_equipment') echo 'active'; ?>">
        <h2>Add New Equipment</h2>
        <form method="post" action="add_equipment.php">
            <label>Name:</label>
            <input type="text" name="name" required><br><br>

            <label>Purchasing Date:</label>
            <input type="date" name="purchasing_date" required><br><br>

            <label>Status:</label>
            <select name="status" required>
                <option value="Assigned">Assigned</option>
                <option value="Need maintenance">Need maintenance</option>
            </select><br><br>

            <button type="submit" name="add_equipment">Add Equipment</button>
        </form>
    </div>
    
    <!-- add to maintenance-->
    <div id="add_maintenance" class="content form-section <?php if($page=='add_maintenance') echo 'active'; ?>">
        <h2>Add Maintenance</h2>
        <form method="post" action="add_maintenance.php">
            <label>Maintenance Type:</label>
            <select name="maintenance_type" required>
                <option value="Regular">Regular</option>
                <option value="Monthly">Monthly</option>
            </select><br><br>

            <label>Cost:</label>
            <input type="number" name="cost" required><br><br>

            <label>Maintenance Date:</label>
            <input type="date" name="maintenance_date" required><br><br>

            <label>Status:</label>
            <select name="status" required>
                <option value="Need maintenance">Need maintenance</option>
            </select><br><br>

            <label>Equipment ID:</label>
            <select name="equipment_id" required>
                <option value="">-- Select Equipment --</option>
                <?php
                $result = $conn->query("SELECT equipment_id, name FROM equipment WHERE status='Assigned'");
                while($row = $result->fetch_assoc()) {
                    echo "<option value='".$row['equipment_id']."'>".$row['equipment_id']." - ".$row['name']."</option>";
                }
                ?>
            </select><br><br>
            <button type="submit" name="add_maintenance">Add Maintenance</button>
        </form>
    </div>

</body>
</html>
