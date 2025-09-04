<?php
session_start();
session_destroy();
header("Location: ../home_page/first_page.html");
exit();
?>