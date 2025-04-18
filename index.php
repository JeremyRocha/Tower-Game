<?php
include 'database.php';
include 'login.php';
include 'battle.php';


$isLoggedIn = isset($_GET['user_id']);

$page = isset($_GET['page']) ? $_GET['page'] : 'login';

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
        <label>
            <input type="text" name="username" placeholder="Enter Username" required>
        </label><br>
        <label>
            <input type="password" name="password" placeholder="Enter Password" required>
        </label><br>
        <button type="submit" name="login">Login</button>
    </form>
    <a href="index.php?page=register">Register</a>
    <?php elseif($page == 'register'):?>
    <h2>Register</h2>
    <form method="post" action="login.php">
        <label>
            <input type="text" name="username" placeholder="Enter Username" required>
        </label><br>
        <label>
            <input type="password" name="password" placeholder="Enter Password" required>
        </label><br>
        <button type="submit" name="register">Register</button>
    </form>
    <a href="index.php">Back to login</a>
    <?php elseif($page == 'home'):?>
    <h2>Welcome to The Forbidden Tower! Name Here!</h2>
    <p>Click play to start you adventure!</p>
    <a href ="index.php?page=shop"><button>Play</button></a>
    <a href="login.php?logout=true"><button>Logout</button></a>
    <?php elseif($page == 'shop'):?>
    <h2>Good luck Name Here!</h2>
    <?php
        $game = new Game();
        $game->startGame();
        $shopItems = $game->getShopItems();
    ?>
    <form method="post" action="battle.php">
    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th>Price</th>
                <th>Select</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td> Potion </td>
                <td> 20</td>
                <td><label>
                        <input type="checkbox" name="items[]" value="Potion">
                    </label></td>
            </tr>
            <?php foreach ($shopItems as $element => $tier){
                if(is_array($tier)) {
                    foreach ($tier as $item => $price) {
                        $name = $element . " " . $item;
                        echo "<tr>
                           <td>$name</td>
                           <td>$price</td>
                           <td><input type='checkbox' name='items[]' value='$name'></td>
                           </tr>";
                    }
                }
            }
          ?>
        </tbody>
    </table>
        <button type="submit" name="items">Purchase</button>
    </form>
    <a href="index.php?page=game"><button>Continue</button></a>
    <a href="index.php?page=home">Back to main screen</a>
    <?php elseif($page == 'game'):?>
    <form method="post" action="battle.php">
        <button type="submit" name ='attack'>Attack</button>
        <button type="submit" name ='Potion'>Potion</button>
    </form>
    <?php endif;?>
</body>
</html>

