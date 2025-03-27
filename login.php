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
        $verify->bindParam(':username', $username); //Binds username to database
        $verify->execute(); //Executes the query
        $user = $verify->fetch(PDO::FETCH_ASSOC);  //Fetch information from database

        if($user && password_verify($password, $user['password'])){ //Check password verification
            $_SESSION['user_id'] = $user['id']; //Stores user id in session
            $_SESSION['username'] = $user['username']; //Store username in session
            return true; //Returns true
        }else{
            return false; //Returns false
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
            return "Username already taken"; //Return the following print
        }

        $storePassword = password_hash($password, PASSWORD_DEFAULT);
        $verify = $this->pdo->prepare("INSERT INTO users (username, password) VALUES (:username, :password)"); //Prepare query
        $verify->bindParam(':username', $username); //Bind username to database
        $verify->bindParam(':password', $storePassword); //Bind password to database
        $verify->execute(); //Executes the query
        return "Registered successfully! Please log in"; //Returns message
    }
}

if(isset($_POST['login'])){ //Check for login
    $login = new Login($pdo); //Create new instance of login with database
    $username = $_POST['username']; //Get username
    $password = $_POST['password']; //Get password

    if($login->login($username, $password)){
        echo "Login successful!"; //Prints the following if login is true
    }else{
        echo "Login failed! Please try again!"; //Print the following if login is false
    }
}

if(isset($_POST['register'])){ //Check for registration
    $register = new Register($pdo); //Creat new instance of register with database
    $username = $_POST['username']; //Get username
    $password = $_POST['password']; //Get password

    $register->register($username, $password); //Registers account through method
    echo "Registered successfully!"; //Prints the following message
}