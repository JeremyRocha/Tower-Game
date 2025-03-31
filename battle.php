<?php

class Player{
    private $playerHealth; //Variable for player health
    private $playerWallet; //Variable to hold money
    private $spellType; //Variable to hold spell type

    public function __construct(){ //Default Constructor
        $this->playerHealth = 100; //Set player health to 100
        $this->playerWallet = 100; //Give player 100 currency
        $this->spellType = null; //Set spell type to null till spell is bought
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
        echo "You don't have enough money!"; //Prints out message
        return false; //Returns false if player doesn't have enough money
    }

    public function equipSpell($spell){ //Method for equipping spell
        $this->spellType = $spell; //Equips spell
    }

    public function getPlayerHealth(){ //Getter for player health
        return $this->playerHealth; //Return the players health
    }

    public function getPlayerWallet(){ //Getter for wallet
        return $this->playerWallet; //Returns amount in wallet
    }

    public function getSpellType(){ //Getter for spell
        return $this->spellType; //Returns spell type
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

    public function takingDamage($damage, $playerElement, $elementWheel){ //Override taking damage
        if($elementWheel[$playerElement] === $this->enemyElement){ //Only takes damage if using right element
            $this->enemyHealth -= $damage; //Calculates damage taken
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