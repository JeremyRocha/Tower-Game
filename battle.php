<?php
include 'player.php';
session_start();

$player = unserialize($_SESSION['player']);
echo "helath" . $player->getPlayerHealth();
//this is working ^

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

    private $enemy;
    private $round;
    private $player;

    public function __construct($player){
        $this->player = $player;
        $this->round = 1;
    }


    public function determineRound(){
        if($this->round > 5){
            echo "You beat the boss and finished the game!";
            header("Location: home.php");
            exit;
        }
        echo "Starting Round!";
        $this->enemy = $this->generateEnemy($this->round);
        //echo "Enemy element". $this->enemy->getEnemyElement(); //Working for enemy
        //echo "Player Health". $this->player->getPlayerWallet(); //Working to get the right of value of object passed used wallet to make sure of that
        //echo "Player ele". $this->player->getElement();
        //echo "Player tier". $this->player->getSpellTier();
        $this->combat();
    }

    public function combat(){
        echo "Your opponent is: " . get_class($this->enemy) . " with ". $this->enemy->getEnemyHealth() . " Health!";
        //echo"HelP play". $this->player->getPlayerHealth();

        while ($this->player->getPlayerHealth() > 0 && $this->enemy->getEnemyHealth() > 0){
            echo "Your health: " . $this->player->getPlayerHealth();
            echo "Enemy health: " . $this->enemy->getEnemyHealth();

            if(isset($_POST['attack'])) {
                $playerSpell = ["tier" => $this->player->getSpellTier(), "element" => $this->player->getElement()];
                if ($this->enemy instanceof Boss) {

                    if ($this->enemy->elementCheck($playerSpell['element'], $this->elementWheel)) {
                        $playerDamage = $this->calculateDamage($playerSpell, $this->enemy->getEnemyElement());
                        $this->enemy->takeDamage($playerDamage);
                        echo "You dealt damage";
                    } else {
                        echo "No Damage";
                    }
                } else {

                    $playerDamage = $this->calculateDamage($playerSpell, $this->enemy->getEnemyElement());
                    $this->enemy->takeDamage($playerDamage);
                    echo " You dealt damage";
                }
            }elseif (isset($_POST['Potion'])){
                if($this->player->getHealthPotion() > 0){
                    $this->player->healing(30);
                    $this->player->addPotion(-1);

                }else{
                    echo "No potions";
                }
            }

            if ($this->enemy->getEnemyHealth() > 0) {
                $enemyDamage = $this->enemy->attack();
                $this->player->takingDamage($enemyDamage);
                echo "Enemy dealt damage";
            }

            if($this->enemy->getEnemyHealth() <= 0){
                echo "You defeated the enemy!";
                $this->player->addMoney(50 * $this->round);
                $_SESSION['player'] = serialize($this->player);
                $this->round++;
                header("Location: shop.php");
                exit;
            }

            if($this->player->getPlayerHealth() <= 0){
                echo "You been defeated!";
                $_SESSION['player'] = serialize($this->player);
                header("Location: home.php");
                exit;
            }
            echo"<form method = 'POST' action = 'battle.php'>";
            echo"<button type= 'submit' name ='attack'>Attack</button>";
            echo"<button type= 'submit' name ='Potion'>Potion</button>";
            echo"</form>";
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
    $game = new Game($player);
$game->determineRound();
//var_dump($_SESSION['player']);
//$enemy = $game->generateEnemy(1);
//var_dump($enemy);
