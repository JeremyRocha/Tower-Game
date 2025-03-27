<?php
session_start(); //Start session
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
        $verify->bindParam(':username', $username); //Binds username from db to variable
        $verify->execute(); //Executes the query
        $user = $verify->fetch(PDO::FETCH_ASSOC);  //Fetch information from database

        if($user && password_verify($password, $user['password'])){
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            return true;
        }else{
            return false;
        }
    }
}

class Register{
    private $pdo;

    public function __construct($pdo){
        $this->pdo = $pdo;
    }

    public function register($username, $password){
        $verify = $this->pdo->prepare("SELECT * FROM users WHERE username = :username");
        $verify->bindParam(':username', $username);
        $verify->execute();
        $user = $verify->fetch(PDO::FETCH_ASSOC);

        if($user){
            return "Username already taken";
        }

        $storePassword = password_hash($password, PASSWORD_DEFAULT);
        $verify = $this->pdo->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
        $verify->bindParam(':username', $username);
        $verify->bindParam(':password', $storePassword);
        $verify->execute();
        return "Registered successfully! Please log in";
    }
}

if(isset($_POST['login'])){
    $login = new Login($pdo);
    $username = $_POST['username'];
    $password = $_POST['password'];

    if($login->login($username, $password)){
        echo "Login successful!";
    }else{
        echo "Login failed! Please try again!";
    }
}

if(isset($_POST['register'])){
    $register = new Register($pdo);
    $username = $_POST['username'];
    $password = $_POST['password'];

    $register->register($username, $password);
    echo "Registered successfully!";
}