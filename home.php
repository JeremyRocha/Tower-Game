<?php session_start();
$username = $_SESSION['username'];
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
<h2>Welcome to The Forbidden Tower! <?php echo"$username"?></h2>
    <p>Click play to start you adventure!</p>
    <form method="post">
        <button type="submit" name='play'>Play</button>
        <button type="submit" name='logout'>Logout</button>
    </form>
<?php
if($_SERVER["REQUEST_METHOD"] == "POST"){

    if(isset($_POST['logout'])){
        session_destroy();
        header('Location: login.php');
        exit;
    }
    if(isset($_POST['play'])){
        header('Location: shop.php');
        exit;
    }
}
?>
</body>
</html>