<?php
include "db.php";

if (isset($_POST['title'])) {
    $title = $conn->real_escape_string($_POST['title']);
    $sql = "UPDATE WORKOUT_PLAYLIST SET like_count = like_count + 1 WHERE title='$title'";
    $conn->query($sql);
}
header("Location: index.php");
exit();
?>

