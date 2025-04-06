<?php
include 'database.php';
class Register{
    private $pdo; //Store database connection


    public function __construct($pdo){ //Default Constructor
        $this->pdo = $pdo; //Stores database
    }

    public function register($username, $password){ //Method for registration
        $verify = $this->pdo->prepare("SELECT * FROM users WHERE username = :username"); //Prepare query
        $verify->bindParam(':username', $username); //Bind username to database
        $verify->execute(); //Executes the query
        $user = $verify->fetch(PDO::FETCH_ASSOC); ;  //Fetch information from database

        if($user){
            echo "<script>alert('Username already taken!'); window.location.href='register.php';</script>";
            //Prints out username already taken in window and redirects back to register
            exit();
        }

        $storePassword = password_hash($password, PASSWORD_DEFAULT);
        $verify = $this->pdo->prepare("INSERT INTO users (username, password) VALUES (:username, :password)"); //Prepare query
        $verify->bindParam(':username', $username); //Bind username to database
        $verify->bindParam(':password', $storePassword); //Bind password to database
        $verify->execute(); //Executes the query
        echo "<script>alert('Registration successful! Please Login'); window.location.href='login.php';</script>";
        //Print message in pop window and redirect to login page

    }

    public function showForm(){
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
<h2>Register</h2>
<form method="post" action="register.php">
    <label>
        <input type="text" name="username" placeholder="Enter Username" required>
    </label><br>
    <label>
        <input type="password" name="password" placeholder="Enter Password" required>
    </label><br>
    <button type="submit" name="register">Register</button>
</form>
<a href="login.php">Back to login</a>
</body>
        </html>
        <?php
    }
}

$register = new Register($pdo);

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])){
    $register->register($_POST['username'], $_POST['password']);
}else{
    $register->showForm();
}