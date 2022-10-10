<?php

$from_card = (isset($_POST['from_card_id'])) ? $_POST['from_card_id'] : '';
$to_card = (isset($_POST['to_card_id'])) ? $_POST['to_card_id'] : '';
$amt = (isset($_POST['amount'])) ? $_POST['amount'] : 0;

try {
    $conn = new PDO("mysql:host=mysql;dbname=test;port=3306", "root", "root");
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "<html><body>";

    echo '<br>
    <form action="index.php" method="post">
    Transfer from: <select name="from_card_id">
    <option value="A">A</option>
    <option value="B">B</option>
    </select><br>
    Transfer To: <select name="to_card_id">
    <option value="A">A</option>
    <option value="B">B</option>
    </select><br>
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
        else if ($from_card !== "A" && $from_card !== "B"){
            echo "Please use a valid Card ID!";
        }
        else if ($to_card !== "A" && $to_card !== "B"){
            echo "Please use a valid Card ID!";
        }
        else {
            $data = ['id' => $from_card];
            $query = $conn->prepare("SELECT value FROM balance where id=:id;");
            $query->execute($data);
            
            if ($amt > $query->fetch()[0]){
                echo "Attempting to transfer amount more than card value!";
            }
            else {
                $data = ['amount' => $amt ,'id' => $to_card];
                $query = $conn->prepare("UPDATE balance SET value = value + :amount where id=:id;");
                $query->execute($data);

                $data = ['amount' => $amt ,'id' => $from_card];
                $query = $conn->prepare("UPDATE balance SET value = value - :amount where id=:id;");
                $query->execute($data);

                echo "Transferred $".$amt." from Card ".$from_card." to Card ".$to_card."!";
            }
        }
    }

    $query = $conn->prepare("SELECT id, value FROM balance;");
    $query->execute();
    echo "<h2>Your Gift card balances:</h2>";
    while($row = $query->fetch(PDO::FETCH_ASSOC)) {
        echo "Card: ".$row["id"].", Balance: ".$row["value"]. ".<br>";
    }

    echo "</body></html>";

  } catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
  }
?>