<?php
include "db.php";
if (isset($_POST['song_title'], $_POST['comment'])) {
    $song_title = $conn->real_escape_string($_POST['song_title']);
    $comment = $conn->real_escape_string($_POST['comment']);

    $sql = "INSERT INTO SONG_COMMENTS (song_title, comment_text) VALUES ('$song_title', '$comment')";
    $conn->query($sql);
}
header("Location: index.php");
exit();
?>
