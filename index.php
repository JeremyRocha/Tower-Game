<?php
session_start();
include 'database.php';
include 'login.php';
include 'battle.php';

$isLoggedIn = isset($_SESSION['user_id']);

$page = isset($_GET['page']) ? $_GET['page'] : 'login';

if($isLoggedIn && $page != 'game') {
    $page = 'home';
}
?>

<!doctype html>
<html lang ="en">
<head>
    <meta charset="UTF-8">
    <meta name ="viewport" content="width=device-width, initial-scale =1">
    <meta name ="robots" content="noindex, nofollow">
    <meta name ="description" content="TowerGame">
    <title>Tower Game</title>
    <link rel="stylesheet" href="CSS/style.css">
</head>
<body>
    <?php if($page == 'login'):?>
    <h2>Login</h2>
    <form method="post" action="login.php">
        <input type="text" name="username" placeholder="Enter Username" required><br>
        <input type="password" name="password" placeholder="Enter Password" required><br>
        <button type="submit" name="login">Login</button>
    </form>
    <a href="index.php?page=register">Register</a>
    <?php elseif($page == 'register'):?>
    <h2>Register</h2>
    <form method="post" action="login.php">
        <input type="text" name="username" placeholder="Enter Username" required><br>
        <input type="password" name="password" placeholder="Enter Password" required><br>
        <button type="submit" name="Register">Register</button>
    </form>
    <a href="index.php">Back to login</a>
    <?php elseif($page == 'home'):?>
    <h2>Welcome to The Forbidden Tower! <?=$_SESSION['username']?>!</h2>
    <p>Click play to start you adventure!</p>
    <a href ="index.php?page=game"><button>Play</button></a>
    <a href="login.php?logout=true"><button>Logout</button></a>
    <?php elseif($page == 'game'):?>
    <h2>Good luck <?=$_SESSION['username']?>!</h2>
    <?php
    $game = new Game();
    $game->startGame();
    ?>
    <a href="index.php?page=home">Back to main screen</a>
    <?php endif; ?>
</body>
</html>

