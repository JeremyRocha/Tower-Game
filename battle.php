<?php

class Player{
    private $playerHealth;
    private $playerWallet;
    private $spellType;

    public function __construct()
    {
        $this->playerHealth = 100;
        $this->playerWallet = 100;
        $this->spellType = 0;
    }

    public function takingDamage($damage)
    {
        $this->playerHealth -= $damage;
    }

    public function healing($healAmount)
    {
        $this->playerHealth += $healAmount;
        if ($this->playerHealth >= 100) {
            $this->playerHealth = 100;
        }
    }

    public function addMoney($money)
    {
        $this->playerWallet += $money;
    }

    public function equipSpell($spell){
        $this->spellType = $spell;
    }

    public function getPlayerHealth()
    {
        return $this->playerHealth;
    }

    public function getPlayerWallet()
    {
        return $this->playerWallet;
    }

    public function getSpellType()
    {
        return $this->spellType;
    }
}
abstract class Enemy{
    protected $enemyHealth;
    protected $enemyElement;

    public function __construct($health, $element){
        $this->enemyHealth = $health;
        $this->enemyElement = $element;
    }

    public function takeDamage($damage){
        $this->enemyHealth -= $damage;
    }

    public function getEnemyHealth(){
        return $this->enemyHealth;
    }

    public function getEnemyElement(){
        return $this->enemyElement;
    }

    abstract public function attack();

}

class NormalEnemy extends Enemy{
    private $health = 100;
    public function __construct($health, $element){
        parent::__construct($health, $element);
    }
    public function attack(){
        return 10;
    }
}

class EliteEnemy extends Enemy{
    private $health = 200;
    public function __construct($health, $element){
        parent::__construct($health, $element);
    }
    public function attack(){
        return 15;
    }
}

class Boss extends Enemy{
    private $health = 400;

    public function __construct($health, $element){
        parent::__construct($health, $element);
    }

    public function attack(){
        return 20;
    }
}

class Game {
    private $elementWheel = [
        "Fire" => "Wind",
        "Wind" => "Lightning",
        "Lightning" => "Earth",
        "Earth" => "Water",
        "Water" => "Fire"];

    public function calculateDamage($playerSpell, $enemyElement){
        $baseDamage = ($playerSpell["tier"] === 1) ? 10 : 20;
        if($this->elementWheel[$playerSpell["element"]] === $enemyElement){
            return $baseDamage * 2;
        }
        return $baseDamage;
    }
}