<?php


$user_id = (isset($_POST['user_id'])) ? $_POST['user_id'] : 0;
$from_card = (isset($_POST['from_card_id'])) ? $_POST['from_card_id'] : '';
$to_card = (isset($_POST['to_card_id'])) ? $_POST['to_card_id'] : '';
$amt = (isset($_POST['amount'])) ? $_POST['amount'] : 0;

try {
    $conn = new PDO("mysql:host=mysql;dbname=test;port=3306", "root", "root");
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "<html><body>";

    echo '<img src="logo.png" width="300" height="auto">';

    $query = $conn->prepare("SELECT * FROM users;");
    $query->execute();

    if ($user_id === 0){
        
        echo '<br>
        <form action="index.php" method="post">
            Select current user: <select name="user_id">';

        while($row = $query->fetch(PDO::FETCH_ASSOC)) {
            echo '<option value="'.$row["id"].'">'.$row["name"].'</option>';
        }

        echo '</select><br>
        <input type="submit">
        </form>';
    }
    else{
        $query = $conn->prepare("SELECT name FROM users WHERE id=:user_id;");
        $query->execute(['user_id' => $user_id]);

        echo '<br><p>Currently logged in as: '.$query->fetch()[0].'</p>';

        echo '
        <form action="index.php" method="post">
        <input type="submit", value="logout">
        </form>';

        $query = $conn->prepare("SELECT * FROM owns LEFT JOIN cards ON card_id=id WHERE user_id=:user_id;");
        $query->execute(['user_id' => $user_id]);

        $res = $query->fetchAll();
        
        $card_ids=array_map(function($row) {return $row["card_id"];}, $res);
        $card_names = array();
        foreach ($res as $row)
            $card_names[$row["card_id"]] = $row["name"];

        echo '<br>
        <form action="index.php" method="post">
            Transfer from: <select name="from_card_id">';
                foreach($res as $row)
                    echo '<option value="'.$row["card_id"].'">'.$row["name"].'</option>';
            echo '</select><br>
            Transfer to: <select name="to_card_id">';
                foreach($res as $row)
                    echo '<option value="'.$row["card_id"].'">'.$row["name"].'</option>';
            echo '</select><br>
            <input type="hidden" name="user_id" value="'.$user_id.'">
                Amount: <input type="number" name="amount"><br>
            <input type="submit">
        </form>';

        if($amt !== 0){
            if ($from_card === $to_card){
                echo "Cannot send to same card!";
            }
            else if (!is_numeric($amt)){
                echo "Transfer amount must be numeric!";
            }
            else if (!in_array($from_card, $card_ids)){
                echo "Please choose a vaid card to transfer from!";
            }
            else if (!in_array($to_card, $card_ids)){
                echo "Please choose a valid card to transfer to!";
            }
            else {
                $query = $conn->prepare("SELECT balance FROM owns where user_id=:user_id AND card_id=:from_card;");
                $query->execute(['user_id' => $user_id, 'from_card' => $from_card]);

                if ($amt > $query->fetch()[0]){
                    echo "Attempting to transfer amount more than card value!";
                }
                else {
                    
                    $query = $conn->prepare("UPDATE owns SET balance = balance + :amount WHERE user_id=:user_id AND card_id=:to_card;");
                    $query->execute(['amount' => $amt, 'user_id' => $user_id, 'to_card' => $to_card]);

                    $query = $conn->prepare("UPDATE owns SET balance = balance - :amount WHERE user_id=:user_id AND card_id=:from_card;");
                    $query->execute(['amount' => $amt, 'user_id' => $user_id, 'from_card' => $from_card]);
                    
                    echo "Transferred $".$amt." from Card ".$card_names[$from_card]." to Card ".$card_names[$to_card]."!";
                }
            }
        }
    }

    $query = $conn->prepare("SELECT name, balance FROM owns LEFT JOIN cards ON card_id=id WHERE user_id=:user_id;");
    $query->execute(['user_id' => $user_id]);
    
    echo "<h2>Your Gift card balances:</h2>";
    while($row = $query->fetch(PDO::FETCH_ASSOC)) {
        echo "Card: ".$row["name"].", Balance: ".$row["balance"]. ".<br>";
    }

    echo "</body></html>";

  } catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
  }
  #xdebug_info();
?>
