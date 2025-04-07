<?php session_start();
class Player{
    private $playerHealth; //Variable for player health
    private $playerWallet; //Variable to hold money
    private $spellTier;//Variable to hold spell tier
    private $element;
    private $healthPotion;

    public function __construct(){ //Default Constructor
        $this->playerHealth = 100; //Set player health to 100
        $this->playerWallet = 100; //Give player 100 currency
        $this->spellTier = null; //Set spell type to null till spell is bought
        $this->element = null;
        $this->healthPotion = 0;
    }

    public function takingDamage($damage){ //Method for player taking damage
        $this->playerHealth -= $damage; //Calculate damage taken
    }

    public function healing($healAmount){ //Method for healing
        $this->playerHealth += $healAmount; //Calculate healing
        if ($this->playerHealth >= 100) { //Checks if health goes above 100
            $this->playerHealth = 100; //Enforce cap of 100 health
        }
    }

    public function addMoney($money){ //Method for adding money
        $this->playerWallet += $money; //Add money to player wallet
    }

    public function spendMoney($amount){ //Method for spending money
        if($this->playerWallet >= $amount){ //Check if player has enough money
            $this->playerWallet -= $amount; //Take away money spent
            return true; //Return true
        }

        return false; //Returns false if player doesn't have enough money
    }

    public function equipSpell($spell){ //Method for equipping spell
        $this->spellTier = $spell['tier']; //Determine spell tier
        $this->element = $spell['element']; //Determine element
    }

    public function addPotion($amount){
        $this->healthPotion += $amount;
    }

    public function getPlayerHealth(){ //Getter for player health
        return $this->playerHealth; //Return the players health
    }

    public function getPlayerWallet(){ //Getter for wallet
        return $this->playerWallet; //Returns amount in wallet
    }

    public function getSpellTier(){ //Getter for spell
        return $this->spellTier; //Returns spell type
    }

    public function getElement(){
        return $this->element;
    }

    public function getHealthPotion(){
        return $this->healthPotion;
    }
}
if(isset($_SESSION['username'])){
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
}else{
    echo "Invalid Session";
}
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if (isset($_POST['logout'])) {
            session_destroy();
            header('Location: login.php');
            exit;
        }
        if (isset($_POST['play'])) {
            $player = new Player();
            $_SESSION['player'] = $player;
            header('Location: shop.php');
            exit;
        }
    }

?>
</body>
</html>