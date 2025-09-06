<?php
include "db.php";
if (isset($_POST['title'], $_POST['link'])) {
    $title = $conn->real_escape_string($_POST['title']);
    $link = $conn->real_escape_string($_POST['link']);

    $sql = "INSERT INTO WORKOUT_PLAYLIST (title, link, theme, suggested_by, like_count)
            VALUES ('$title', '$link', 'General', 'User', 0)";

    if ($conn->query($sql)) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error adding song: " . $conn->error;
    }
} else {
    echo "Invalid input.";
}
?>

