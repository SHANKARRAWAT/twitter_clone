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

$sql = "SELECT t.*, u.username, u.profile_pic FROM tweets t
        JOIN users u ON t.user_id = u.id
        ORDER BY t.created_at DESC";
$tweets = $conn->query($sql);

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Timeline</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Twitter Clone</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="timeline.php">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="profile.php">Profile</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="tweet-input mb-4">
            <form action="post_tweet.php" method="post" enctype="multipart/form-data">
                <textarea class="form-control" rows="3" name="content" placeholder="What's happening?" required></textarea>
                <input type="file" name="media" class="form-control mt-2">
                <button type="submit" class="btn btn-primary btn-block mt-2">Tweet</button>
            </form>
        </div>

        <div class="tweet-feed">
            <?php if ($tweets->num_rows > 0): ?>
                <?php while($tweet = $tweets->fetch_assoc()): ?>
                    <div class="tweet mb-3">
                        <div class="d-flex">
                            <img src="uploads/<?php echo $tweet['profile_pic']; ?>" alt="Profile Picture" class="rounded-circle" width="50" height="50">
                            <div class="ml-3">
                                <p><strong><?php echo $tweet['username']; ?></strong> <span class="text-muted">@<?php echo $tweet['username']; ?></span></p>
                                <p><?php echo $tweet['content']; ?></p>
                                <?php if ($tweet['media']): ?>
                                    <?php if (strpos($tweet['media'], 'mp4') !== false): ?>
                                        <video width="100%" controls>
                                            <source src="uploads/<?php echo $tweet['media']; ?>" type="video/mp4">
                                            Your browser does not support the video tag.
                                        </video>
                                    <?php else: ?>
                                        <img src="uploads/<?php echo $tweet['media']; ?>" alt="Tweet Media" width="100%">
                                    <?php endif; ?>
                                <?php endif; ?>
                                <div>
                                    <button type="button" class="btn btn-link like-btn" data-tweet-id="<?php echo $tweet['id']; ?>">
                                        Like <span class="like-count"><?php echo $tweet['like_count']; ?></span>
                                    </button>
                                    <form class="d-inline retweet-form" method="post">
                                        <input type="hidden" name="tweet_id" value="<?php echo $tweet['id']; ?>">
                                        <button type="submit" class="btn btn-link">Retweet</button>
                                    </form>
                                    <button class="btn btn-link">Comment</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No tweets to display.</p>
            <?php endif; ?>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.like-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var tweetId = this.getAttribute('data-tweet-id');
                    var likeBtn = this;

                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', 'like.php', true);
                    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState == 4 && xhr.status == 200) {
                            try {
                                var response = JSON.parse(xhr.responseText);
                                if (response.success) {
                                    likeBtn.querySelector('.like-count').textContent = response.like_count;
                                } else {
                                    alert('Error: ' + response.message);
                                }
                            } catch (e) {
                                console.error('Invalid JSON response:', xhr.responseText);
                                alert('An error occurred. Please try again.');
                            }
                        }
                    };
                    xhr.send('tweet_id=' + tweetId);
                });
            });
        });
    </script>
</body>
</html>
