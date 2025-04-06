<?php
session_start();
include 'database.php'; //Includes logic for database
class Login{
    private $pdo; //Variable for database

    //Default Constructor
    public function __construct($pdo){
        $this->pdo = $pdo; //Stores database when constructor is used
    }

    //Method for logging in
    public function login($username, $password){
        $verify = $this->pdo->prepare("SELECT * FROM users WHERE username = :username"); //Prepare query to find username
        $verify->bindParam(':username', $username); //Binds username to database
        $verify->execute(); //Executes the query
        $user = $verify->fetch(PDO::FETCH_ASSOC);  //Fetch information from database

        if($user && password_verify($password, $user['password'])){ //Check password verification
            $_SESSION['user_id'] = $user['id']; //Stores user id in session
            $_SESSION['username'] = $user['username']; //Store username in session
            header('Location: home.php');
            exit();
        }else{
            echo "<script>alert('Login Failed! Please try again!'); window.location.href='login.php';</script>";

            $this->showForm();
        }
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
    <a href="register.php">Register</a>
</body>
</html>
<?php
    }
}


$login = new Login($pdo);

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])){
    $login->login($_POST['username'], $_POST['password']);
}else{
    $login->showForm();
}

if(isset($_GET['logout'])){
    session_destroy();
    header("Location: index.php?page=login");
    exit(); //Exit program
}