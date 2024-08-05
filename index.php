<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

?>

<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "micro_twitter";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";

    if ($conn->query($sql) === TRUE) {
        header("Location: login.html");
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
    <title>Clone</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet" href="/Twitter clone/css/style.css" type="text/css">

</head>

<body>
    <div class="login">
        <nav>
            <div><a href="http://">Home</a></div>
            <a href="http://">About</a>

            <div class="languages">
                <label for="languages">languages:</label>
                <select name="languages" id="languages">
                    <option value="English">English</option>
                    <option value="Hindi">Hindi</option>
                    <option value="Urdu">Urdu</option>
                    <option value="french">french</option>
                </select>
            </div>
        </nav>
        <form action="/twitter_clone/signup.php" method="POST">
            <h3>Login in To Twitter</h3>
            <div class="form-group my-1">

            <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="usernamehelp"
                placeholder="username" name="username">
            </div>
            
            <div class="form-group my-1">
            <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="usernamehelp"
                placeholder="email" name="email">
            </div>
            

            <div class="form-group my-1">

                <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password" name="password">
            </div>

            <div class="text-field">
                <button type="submit" class="btn btn-primary">Submit</button>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                    <label class="form-check-label" for="flexCheckDefault">
                        Default checkbox
                    </label>
                </div>
                <div> <a href="http://">Forgot password ?</a> </div>
            </div>
        </form>
    </div>
</body>


</html>