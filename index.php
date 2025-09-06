<?php include "db.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Workout Beats</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <header>
    <h1>🎵 Workout Beats</h1>
  </header>
  <div class="container">
    <div class="form-section">
      <h2>Share Your Workout Beat</h2>
      <form method="POST" action="submit_song.php">
        <input type="text" name="title" placeholder="Enter song title..." required>
        <input type="text" name="link" placeholder="Paste YouTube link here..." required>
        <button type="submit">Share Song</button>
      </form>
    </div>
    <hr>
    <?php
    function youtube_embed($url) {
        $parsedUrl = parse_url($url);
        if (isset($parsedUrl['host']) && strpos($parsedUrl['host'], 'youtu.be') !== false) {
            $video_id = ltrim($parsedUrl['path'], '/');
            return "https://www.youtube.com/embed/" . $video_id;
        }
        if (isset($parsedUrl['host']) && strpos($parsedUrl['host'], 'youtube.com') !== false && isset($parsedUrl['query'])) {
            parse_str($parsedUrl['query'], $params);
            if (isset($params['v'])) {
                return "https://www.youtube.com/embed/" . $params['v'];
            }
        }
        if (strpos($url, "embed/") !== false) {
            return $url;
        }
        return $url;
    }
    $sql = "SELECT * FROM WORKOUT_PLAYLIST ORDER BY like_count DESC";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $embed_url = youtube_embed($row['link']);
        echo "<div class='song-card'>";
        echo "<h3>" . htmlspecialchars($row['title']) . "</h3>";
        echo "<iframe width='400' height='225' src='" . $embed_url . "' allowfullscreen></iframe>";
        echo "<form method='POST' action='like.php'>
                <input type='hidden' name='title' value='" . htmlspecialchars($row['title'], ENT_QUOTES) . "'>
                <button type='submit'>👍 Like (" . $row['like_count'] . ")</button>
              </form>";
        echo "<form method='POST' action='submit_comment.php'>
                <input type='hidden' name='song_title' value='" . htmlspecialchars($row['title'], ENT_QUOTES) . "'>
                <input type='text' name='comment' placeholder='Add a comment...' required>
                <button type='submit'>💬 Comment</button>
              </form>";
        $comments_sql = "SELECT * FROM SONG_COMMENTS WHERE song_title='" . $conn->real_escape_string($row['title']) . "'";
        $comments_result = $conn->query($comments_sql);
        if ($comments_result && $comments_result->num_rows > 0) {
          echo "<ul class='comments'>";
          while ($comment = $comments_result->fetch_assoc()) {
            echo "<li>" . htmlspecialchars($comment['comment_text']) . "</li>";
          }
          echo "</ul>";
        }
        echo "</div><hr>";
      }
    } else {
      echo "<p>No songs shared yet!</p>";
    }
    ?>
  </div>
</body>
</html>