<?php
session_start();
Class Shop{
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
    private $player;

    public function __construct($player){
        $this->player = $player;
    }

    public function purchase(){

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $selectedItems = $_POST["items"] ?? [];

            foreach ($selectedItems as $item) {

                if (isset($this->shopItems[$item]) && !is_array($this->shopItems[$item])) {
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

    public function showShop(){
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
<form method="post" action="shop.php">
    <table>
        <thead>
        <tr>
            <th>Item</th>
            <th>Price</th>
            <th>Select</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td> Potion </td>
            <td> 20</td>
            <td><label>
                    <input type="checkbox" name="items[]" value="Potion">
                </label></td>
        </tr>
        <?php foreach ($this->shopItems as $element => $tier){
            if(is_array($tier)) {
                foreach ($tier as $item => $price) {
                    $name = $element . " " . $item;
                    echo "<tr>
                           <td>$name</td>
                           <td>$price</td>
                           <td><input type='checkbox' name='items[]' value='$name'></td>
                           </tr>";
                }
            }
        }
        ?>
        </tbody>
    </table>
    <button type="submit" name="items">Purchase</button>
</form>
<a href="home.php">Return to home</a>
<a href="battle.php">Continue to game</a>
</body>
</html>
<?php
    }
}

if(isset($_SESSION['username'])){

    $player = unserialize($_SESSION['player']);
    $shop = new Shop($player);
    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['items'])){
        $shop->purchase();
        $_SESSION['player'] = serialize($player);
    }else{
        $shop->showShop();
    }
}else{
    echo"Invalid Session";
}