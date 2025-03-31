<?php
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
            header('Location: index.php?page=home');
            exit();
        }else{
            echo "<script>alert('Login Failed! Please try again!'); window.location.href='index.php?page=login';</script>";
            exit();
        }
    }
}

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
            echo "<script>alert('Username already taken!'); window.location.href='index.php?page=register';</script>";
            //Prints out username already taken in window and redirects back to register
            exit();
        }

        $storePassword = password_hash($password, PASSWORD_DEFAULT);
        $verify = $this->pdo->prepare("INSERT INTO users (username, password) VALUES (:username, :password)"); //Prepare query
        $verify->bindParam(':username', $username); //Bind username to database
        $verify->bindParam(':password', $storePassword); //Bind password to database
        $verify->execute(); //Executes the query
        echo "<script>alert('Registration successful! Please Login'); window.location.href='index.php?page=login';</script>";
        //Print message in pop window and redirect to login page

        exit();
    }
}


if(isset($_POST['login'])){ //Check for login
    $login = new Login($pdo);
    $login->login($_POST['username'], $_POST['password']);
}

if(isset($_POST['register'])){ //Check for registration
    $register = new Register($pdo);
    $register->register($_POST['username'], $_POST['password']);
}

if(isset($_GET['logout'])){
    session_destroy(); //Destroys session
    header("Location: index.php");
    exit(); //Exit program
}