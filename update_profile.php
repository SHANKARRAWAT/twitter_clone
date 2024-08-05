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
    $bio = $_POST['bio'];
    $profile_pic = $_FILES['profile_pic']['name'];
    $birthday=$_POST['birthday'];
    $location=$_POST['location'];

    if ($profile_pic) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($profile_pic);
        move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file);
        // if($bio)
        // $sql = "UPDATE users SET bio='$bio' WHERE id='$user_id'";
        // if($profile_pic)
        // $sql = "UPDATE users SET bio='$bio', profile_pic='$profile_pic' WHERE id='$user_id'";
        // if($birthday)
        // $sql = "UPDATE users SET birthday='$birthday' WHERE id='$user_id'";
          echo($uer['location']);
         $sql = "UPDATE users SET bio='$bio', profile_pic='$profile_pic',birthday='$birthday',location='$location' WHERE id='$user_id'";
    } else {
        $sql = "UPDATE users SET bio='$bio',birthday='$birthday',location='$location' WHERE id='$user_id'";
    }

    if ($conn->query($sql) === TRUE) {
        header("Location: profile.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
