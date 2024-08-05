<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

?>


<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "micro_twitter";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $content = $_POST['content'];
    $image = $_FILES['media']['name'];
    // $video = $_FILES['video']['name'];
   if(!$content) 
   {header("Location: profile.php");
   exit;
   }
    $sql = "INSERT INTO tweets (user_id, content, image, video) VALUES ('$user_id', '$content', '$image', '$video')";

    if ($conn->query($sql) === TRUE) {
        if ($image) {
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($image);
            move_uploaded_file($_FILES["media"]["tmp_name"], $target_file);
        }
        if ($video) {
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($video);
            move_uploaded_file($_FILES["video"]["tmp_name"], $target_file);
        }
        header("Location: profile.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}



$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>twitter_clone</title>
    <link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha.6/css/bootstrap.min.css'>
  <link rel='stylesheet prefetch' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css'>
  <link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/octicons/4.4.0/font/octicons.min.css'>
  <!-- Custom css-->
  <link rel="stylesheet" href="/twitter_clone/style/style.css">
</head>
<style>
    .container{
        max-width:30rem;
    }
</style>
<body>
<div class="tweet-input mb-4 container">
                    <form action="post_tweet.php" method="post" enctype="multipart/form-data">
                        <textarea class="form-control" rows="3" name="content" placeholder="What's happening?"></textarea>
                        <input type="file" name="media" class="form-control mt-2">
                        <button type="submit" class="btn btn-primary btn-block mt-2">Tweet</button>
                    </form>

</div>
</body>
</html>
