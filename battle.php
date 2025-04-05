<?php

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
abstract class Enemy{
    protected $enemyHealth; //Variable for enemy health
    protected $enemyElement; //Variable for enemy element

    public function __construct($health, $element){ //Default constructor
        $this->enemyHealth = $health; //Sets enemy health
        $this->enemyElement = $element; //Sets enemy element
    }

    public function takeDamage($damage){ //Method for taking damage
        $this->enemyHealth -= $damage; //Calculate damage
    }

    public function getEnemyHealth(){ //Getter for health
        return $this->enemyHealth; //Returns enemy health
    }

    public function getEnemyElement(){ //Getter for element
        return $this->enemyElement; //Return enemy element
    }

    abstract public function attack(); //Method for enemy attack

}

class NormalEnemy extends Enemy{

    public function __construct($element){ //Default constructor
        parent::__construct(100, $element); //Calls parent constructor with given parameters
    }
    public function attack(){ //Override attack function
        return 10; //Enemy deals 10 damage
    }
}

class EliteEnemy extends Enemy{
    public function __construct($element){ //Default Constructor
        parent::__construct(200, $element); //Calls parent constructor with given parameters
    }
    public function attack(){ //Override attack function
        return 15; //Elite does 15 damage
    }
}

class Boss extends Enemy{

    public function __construct($element){ //Default constructor
        parent::__construct(400, $element); //Calls parent constructor with given parameters
    }

    public function elementCheck($playerElement, $elementWheel){ //Override taking damage
        if($elementWheel[$playerElement] === $this->enemyElement){ //Only takes damage if using right element
            return true; //Return true
        }
        return false; //Return false
    }

    public function attack(){ //Override attack
        return 20; //Boss deals 20 damage
    }
}

class Game {

    //Element wheel determine what elements are stronger against certain elements
    private $elementWheel = [
        "Fire" => "Wind",
        "Wind" => "Lightning",
        "Lightning" => "Earth",
        "Earth" => "Water",
        "Water" => "Fire"];
    private $shopItems = [
        "Potion" => 20,
        "Fire" => [
            "Tier 1 Spell" => 50,
            "Tier 2 Spell" => 100,
            "Tier 3 Spell" => 200,
        ],
        "Wind" => [
            "Tier 1 Spell" => 50,
            "Tier 2 Spell" => 100,
            "Tier 3 Spell" => 200,
        ],
        "Lightning" => [
            "Tier 1 Spell" => 50,
            "Tier 2 Spell" => 100,
            "Tier 3 Spell" => 200,
        ],
        "Earth" => [
            "Tier 1 Spell" => 50,
            "Tier 2 Spell" => 100,
            "Tier 3 Spell" => 200,
        ],
        "Water" => [
            "Tier 1 Spell" => 50,
            "Tier 2 Spell" => 100,
            "Tier 3 Spell" => 200,
        ]
    ];
    private $enemy;
    private $round;
    private $player;

    public function __construct(){
        $this->player = new Player();
        $this->round = 1;
    }

    public function startGame(){
        echo "Welcome to the Tower!";

        $this->shop();
        $this->determineRound();
    }

    public function shop(){

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $selectedItems = $_POST["items"] ?? [];

            foreach ($selectedItems as $item) {

                if ($item == "Potion") {
                    $price = $this->shopItems[$item];
                    if ($this->player->spendMoney($price)) {
                        $this->player->addPotion(1);
                        echo "Potion bought";
                    } else {
                        echo "You don't have enough money!";
                    }
                } else {
                    $split = explode(" ", $item);
                    $element = $split[0];
                    $spellAccess = $split[1] . " " . $split[2] . " " . $split[3];
                    $tier = (int)$split[2];

                    if (isset($this->shopItems[$element][$spellAccess])) {
                        $price = $this->shopItems[$element][$spellAccess];
                        if ($this->player->spendMoney($price)) {
                            $this->player->equipSpell(["element" => $element, "tier" => $tier]);
                            echo "bought spell";
                        }
                    }else{
                        echo "No spell";
                    }
                }
            }
        }
    }
    public function determineRound(){
        if($this->round > 5){
            echo "You beat the boss and finished the game!";
            return;
        }
        echo "Starting Round!";
        $this->enemy = $this->generateEnemy($this->round);
        $this->combat();
    }

    public function combat(){
        echo "Your opponent is: " . get_class($this->enemy) . " with ". $this->enemy->getEnemyHealth() . " Health!";

        while ($this->player->getPlayerHealth() > 0 && $this->enemy->getEnemyHealth() > 0){
            echo "Your health: " . $this->player->getPlayerHealth();
            echo "Enemy health: " . $this->enemy->getEnemyHealth();

            if($this->enemy->getEnemyHealth() <= 0){
                echo "You defeated the enemy!";
                $this->player->addMoney(50 * $this->round);
                $this->round++;
                $this->shop();
                $this->determineRound();
                return;
            }

            if(isset($_POST['attack'])){
                if($this->enemy instanceof Boss){
                    $playerSpell = ["tier" => $this->player->getSpellTier(), "element" => $this->player->getElement()];
                    if($this->enemy->elementCheck($playerSpell['element'], $this->elementWheel)){
                        $playerDamage = $this->calculateDamage($playerSpell, $this->enemy->getEnemyElement());
                        $this->enemy->takeDamage($playerDamage);
                        echo "You dealt damage";
                    }else{
                        echo "No Damage";
                    }
                }else {
                    $playerSpell = ["tier" => $this->player->getSpellTier(), "element" => $this->player->getElement()];
                    $playerDamage = $this->calculateDamage($playerSpell, $this->enemy->getEnemyElement());
                    $this->enemy->takeDamage($playerDamage);
                    echo " You dealt damage";
                }
                $enemyDamage = $this->enemy->attack();
                $this->player->takingDamage($enemyDamage);
                echo "Enemy dealt damage";
            }elseif (isset($_POST['Potion'])){
                if($this->player->getHealthPotion() > 0){
                    $this->player->healing(30);
                    $this->player->addPotion(-1);
                }else{
                    echo "No potions";
                }
            }

            if($this->player->getPlayerHealth() <= 0){
                echo "You been defeated!";
                header("Location: index.php?page=home");
            }
        }
    }

    public function generateEnemy($round){ //Method for generating enemy
        $elements = ["Fire", "Wind", "Lightning", "Earth", "Water"]; //List of elements
        $randomElement = $elements[array_rand($elements)]; //Generates random element from list

        if($round <= 2){ //Check for round 1 and 2
            return new NormalEnemy($randomElement); //Returns a normal enemy
        }elseif ($round <= 4){ //Check for round 3 and 4
            return new EliteEnemy($randomElement); //Returns an elite enemy
        }else{ //Else return boss on final round
            return new Boss($randomElement); //Returns the boss
        }
    }

    public function calculateDamage($playerSpell, $enemyElement){ //Method for calculating damage
        $baseDamage = match ($playerSpell["tier"]){ //Matches spell tier to corresponding damage
            1 => 10,
            2 => 20,
            3 => 40,
            default => 10
        };

        if($this->elementWheel[$playerSpell["element"]] === $enemyElement){ //Checks for element weakness
            return $baseDamage * 2; //Return double damage if enemy is weak to element
        }
        return $baseDamage; //Returns normal damage
    }
}

