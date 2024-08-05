
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "micro_twitter";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error]);
    exit();
}

$user_id = $_SESSION['user_id'];
$tweet_id = $_POST['tweet_id'];

// Debugging output
error_log("User ID: $user_id");
error_log("Tweet ID: $tweet_id");

// Check if the tweet is already liked by the user
$sql = "SELECT * FROM likes WHERE user_id='$user_id' AND tweet_id='$tweet_id'";
$result = $conn->query($sql);

if ($result === FALSE) {
    echo json_encode(['success' => false, 'message' => $conn->error]);
    $conn->close();
    exit();
}

if ($result->num_rows > 0) {
    // If already liked, remove the like
    $sql = "DELETE FROM likes WHERE user_id='$user_id' AND tweet_id='$tweet_id'";
    if ($conn->query($sql) === FALSE) {
        echo json_encode(['success' => false, 'message' => $conn->error]);
        $conn->close();
        exit();
    }
    // Decrease like count
    $sql = "UPDATE tweets SET like_count = like_count - 1 WHERE id='$tweet_id'";
    if ($conn->query($sql) === FALSE) {
        echo json_encode(['success' => false, 'message' => $conn->error]);
        $conn->close();
        exit();
    }
} else {
    // If not liked, add a like
    $sql = "INSERT INTO likes (user_id, tweet_id) VALUES ('$user_id', '$tweet_id')";
    if ($conn->query($sql) === FALSE) {
        echo json_encode(['success' => false, 'message' => $conn->error]);
        $conn->close();
        exit();
    }
    // Increase like count
    $sql = "UPDATE tweets SET like_count = like_count + 1 WHERE id='$tweet_id'";
    if ($conn->query($sql) === FALSE) {
        echo json_encode(['success' => false, 'message' => $conn->error]);
        $conn->close();
        exit();
    }
}

// Get the new like count
$sql_likes = "SELECT like_count FROM tweets WHERE id='$tweet_id'";
$result_likes = $conn->query($sql_likes);

if ($result_likes === FALSE) {
    echo json_encode(['success' => false, 'message' => $conn->error]);
    $conn->close();
    exit();
}

$like_count = $result_likes->fetch_assoc()['like_count'];

echo json_encode(['success' => true, 'like_count' => $like_count]);

$conn->close();
?>
