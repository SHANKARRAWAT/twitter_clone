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

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id='$user_id'";
$result=$conn->query($sql);




$allusers="select * from users";
$all_users=$conn->query($allusers);

$sql_tweets = "SELECT * FROM tweets WHERE user_id='$user_id' ORDER BY created_at DESC";
$tweets = $conn->query($sql_tweets);

$sql_tweet_count = "SELECT COUNT(*) AS tweet_count FROM tweets WHERE user_id='$user_id'";
$result_tweet_count = $conn->query($sql_tweet_count);
$tweet_count = $result_tweet_count->fetch_assoc()['tweet_count'];

$sql_display_all = "SELECT t.*, u.username, u.profile_pic FROM tweets t
        JOIN users u ON t.user_id = u.id
        ORDER BY t.created_at DESC";

  $display_tweet=$conn->query($sql_display_all);
  
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "User not found";
    exit();
}

$conn->close();
?>


<!DOCTYPE html>
<html >
<head>
  <meta charset="UTF-8">
  <title>twitter_clone</title>
  
  
  <link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha.6/css/bootstrap.min.css'>
  <link rel='stylesheet prefetch' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css'>
  <link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/octicons/4.4.0/font/octicons.min.css'>
  <!-- Custom css-->
  <link rel="stylesheet" href="/twitter_clone/style/style.css">


  <style>
        .containeredit {
            max-width: 30rem;
            background-color: aqua;
            position: absolute;
            top: 280%;
            left: 12%;
            transform: translate(-50%, -50%);
            width: 30%;

            background-color: white;
            padding: 2rem;
            border-radius: 5px;
            box-shadow: 0 3rem 5rem rgba(0, 0, 0, 0.3);
            z-index: 10;
        }

        .form-group {
            margin-bottom: .5rem;

        }

        .input {
            padding: .1rem .4rem;
            font-size: .8rem;
        }

        .hidden {
            display: none;
        }
    </style>
  
</head>

<body>
  <!-- Fixed top navbar -->
<nav class="navbar navbar-toggleable-md fixed-top">
  <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse container">
    <!-- Navbar navigation links -->
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="#"><i class="octicon octicon-home" aria-hidden="true"></i> Home <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#"><i class="octicon octicon-zap"></i> Moments</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#"><i class="octicon octicon-bell"></i> Notifications</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#"><i class="octicon octicon-inbox"></i> Messages</a>
      </li>
    </ul>
    <!-- END: Navbar navigation links -->
    <!-- Navbar Search form -->
    <form class="navbar-form" role="search">
      <div class="input-group">
        <input type="text" class="form-control input-search" placeholder="Search Twitter" name="srch-term" id="srch-term">
        <div class="input-group-btn">
          <button class="btn btn-default btn-search" type="submit"><i class="octicon octicon-search navbar-search-icon"></i></button>
        </div>
      </div>
    </form>
    <!-- END: Navbar Search form -->
    <!-- Navbar User menu -->
    <div class="dropdown navbar-user-dropdown">
      <!-- <button class="btn btn-secondary dropdown-toggle btn-circle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> </button> -->
      <img class="profile_pic btn-circle"
      src="uploads/<?php echo $user['profile_pic']; ?>" alt="Profile Picture" width="auto" height="auto">
      <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
        <a class="dropdown-item" href="#">Action</a>
        <a class="dropdown-item" href="#">Another action</a>
        <a class="dropdown-item" href="#">Something else here</a>
      </div>
    </div>
    <!-- END: Navbar User menu -->
    <!-- Navbar Tweet button -->
    <button class="btn btn-search-bar"><a href="/twitter_clone/post_tweet.php">Tweet</a> </button>
  </div>
</nav>
<!-- END: Fixed top navbar -->
<div class="main-container">

  <!-- Profile background large image -->
  <div class="row profile-background">
    <div class="container">
	  <!-- User main avatar -->
    
      <div class="avatar-container">
        <div class="avatar">
         
        <img class="profile_pic"
        src="uploads/<?php echo $user['profile_pic']; ?>" alt="Profile Picture" width="auto" height="150">
        </div>
      </div>
    </div>
  </div>

  <nav class="navbar profile-stats">
    <div class="container">
      <div class="row">
        <div class="col">

        </div>
        <div class="col-6">
          <ul>
            <li class="profile-stats-item-active">
              <a>
                <span class="profile-stats-item profile-stats-item-label">Tweets</span>
                <span class="profile-stats-item profile-stats-item-number"><?php   echo($tweet_count)?></span>
              </a>
            </li>
            <li>
              <a>
                <span class="profile-stats-item profile-stats-item-label">Following</span>
                <span class="profile-stats-item profile-stats-item-number">420</span>
              </a>
            </li>
            <li>
              <a>
                <span class="profile-stats-item profile-stats-item-label">Followers</span>
                <span class="profile-stats-item profile-stats-item-number">583</span>
              </a>
            </li>
            <li>
              <a>
                <span class="profile-stats-item profile-stats-item-label">Likes</span>
                <span class="profile-stats-item profile-stats-item-number">241</span>
              </a>
            </li>
          </ul>
        </div>

        <div class="col edit" >
         <button class="btnedit">Edit profile</button>
        </div>

 
 
 
        <div class="containeredit hidden">
<form action="update_profile.php" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <input type="file" class="form-control input" id="profile_pic" name="profile_pic" placeholder="profile_pic">
    </div>


    <div class="form-group">
        <input type="text" class="form-control input" id="bio" name="bio" placeholder="bio" value= "<?php echo($user["bio"]) ?>" >
    </div>

    <div class="form-group">
        <input type="text" class="form-control input" id="location" name="location" placeholder="location"  value= "<?php echo($user["location"]) ?>">
    </div>

    <div class="form-group">
        <input type="text" class="form-control input" id="birthday" name="birthday" placeholder="birthday"  value= "<?php echo($user["birthday"]) ?>">
    </div>


    <button type="submit" class="btn btn-primary">Save Changes</button>
</form>
</div>


</div>
    </div>
  </nav>


  <div class="container main-content">
    <div class="row">
      <div class="col profile-col">
        <!-- Left column -->
        <div class="profile-header">
          <!-- Header information -->
          <h3 class="profile-fullname"><a><?php echo($user["username"]) ?><a></h3>
          <h2 class="profile-element"><a> <?php echo($user["username"]) ?></a></h2>
          <a class="profile-element profile-website" hrerf=""><i class="octicon octicon-link"></i>location :<?php echo $user["location"]?></a>
          <a class="profile-element profile-website" hrerf=""><i class="octicon octicon-location"></i> <?php echo $user["location"]?></a>
          <h2 class="profile-element"><i class="octicon octicon-calendar"></i> joined:<?php echo($user["joined_date"])?></h2>
          <button class="btn btn-search-bar tweet-to-btn"></button>
          <a class="profile-element profile-website" hrerf=""><i class="octicon octicon-file-media"></i>1,142 Photos and videos</a>
          <div class="pic-grid">
            <!-- Image grid -->
            <div class="row">
              <div class="col pic-col"><img src="https://pbs.twimg.com/media/DFCq7iTXkAADXE-.jpg:thumb" height="73px" class=""></div>
              <div class="col pic-col"><img src="https://pbs.twimg.com/media/DEoQ0vyXoBA1cwQ.png:thumb" height="73px" class=""></div>
              <div class="col pic-col"><img src="https://pbs.twimg.com/media/DDVbW4RXsAAasuw.jpg:thumb" height="73px" class=""></div>
            </div>
            <!-- End: row -->
            <div class="row">
              <div class="col pic-col"><img src="https://media.istockphoto.com/id/1335941248/photo/shot-of-a-handsome-young-man-standing-against-a-grey-background.jpg?s=1024x1024&w=is&k=20&c=82oh6AmEb8LFipL3cE0gZwu4t45sT5G3386m82MHz6k=" height="73px" class=""></div>
              <div class="col pic-col"><img src="https://pbs.twimg.com/media/DEoQ0vyXoBA1cwQ.png:thumb" height="73px" class=""></div>
              <div class="col pic-col"><img src="https://pbs.twimg.com/media/DDVbW4RXsAAasuw.jpg:thumb" height="73px" class=""></div>
            </div>
            <!-- End: row -->
          </div>
          <!-- End: image grid -->
        </div>
      </div>
      <!-- End; Left column -->
      <!-- Center content column -->
      <div class="col-6">
        <ol class="tweet-list">
        <div class="tweet-feed">
                   <div class="">
                    <?php while($tweet = $tweets->fetch_assoc()): ?>
                        <div class="">
                            <div class="">
                              
                                <img src="uploads/<?php echo $user['profile_pic']; ?>" alt="Profile Picture" class="rounded-circle" width="50" height="50">
                                 <div class="ml-3">
                                    <p><strong><?php echo $user['username']; ?></strong> <span class="text-muted">@<?php echo $user['username']; ?></span></p>
                                     <p><?php echo $tweet['content']; ?></p>
                                    <p> <img src="uploads/<?php echo $tweet['image']; ?>" alt="Tweet Media" width="30%"></p> 
                                    
                                     <div> 

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
                        </div>
                    <?php endwhile; ?>
                    </div>
                </div>
            </div>

        </ol>

        <div class="col right-col">
        <div class="content-panel">
          <div class="panel-header">
            <h4>Who to follow</h4><small><a href="">Refresh</a></small><small><a href="">View all</a></small>
          </div>
          <!-- Who to Follow panel -->
          <div class="panel-content">
            <!--Follow list -->
            <ol class="tweet-list">
              <li class="tweet-card">
                <div class="tweet-content">
                  <img class="tweet-card-avatar" src="https://media.istockphoto.com/id/1335941248/photo/shot-of-a-handsome-young-man-standing-against-a-grey-background.jpg?s=1024x1024&w=is&k=20&c=82oh6AmEb8LFipL3cE0gZwu4t45sT5G3386m82MHz6k=" alt="">
                  <div class="tweet-header">
                    <span class="fullname">
                  <strong>Jon Vadillo</strong>
                </span>
                    <span class="username">@JonVadillo</span>
                  </div>
                  <button class="btn btn-follow">Follow</button>
                </div>
              </li>
              
             
              <?php while($users = $all_users->fetch_assoc()): ?>
              <li class="tweet-card">
                      <div class="tweet-content">
                                <img class="tweet-card-avatar" src="uploads/<?php echo $users['profile_pic']; ?>" alt="Profile Picture" class="rounded-circle" width="50" height="50">
                                <div class="tweet-header">
                                      <span class="fullname">
                                      <strong><?php echo $users['username']; ?> </strong>
                                  </span>
                                      <span class="username">@<?php echo $users['username']; ?></span>
                                  </div>
                              
                                  <button class="btn btn-follow">Follow</button>
              <?php endwhile; ?>

                   
                      </div>

                
            
              </li>
            </ol>
            <!--END: Follow list -->
          </div>
        </div>
      </div>
        <!-- End: tweet list -->
      </div>
      <!-- End: Center content column -->
     
   </div>
    </div>
  </div>


<script src="/twitter_clone/javascript/index.js"></script>


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
                if (xhr.readyState == 4) {
                    if (xhr.status == 200) {
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
                    } else {
                        console.error('Request failed with status:', xhr.status);
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