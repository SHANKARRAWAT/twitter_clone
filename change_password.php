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
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    $sql = "SELECT password FROM users WHERE id='$user_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($current_password, $row['password'])) {
            if ($new_password === $confirm_password) {
                $new_password_hashed = password_hash($new_password, PASSWORD_DEFAULT);
                $sql = "UPDATE users SET password='$new_password_hashed' WHERE id='$user_id'";
                if ($conn->query($sql) === TRUE) {
                    echo "Password updated successfully";
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            } else {
                echo "New passwords do not match";
            }
        } else {
            echo "Current password is incorrect";
        }
    } else {
        echo "User not found";
    }
}

$conn->close();
?>
