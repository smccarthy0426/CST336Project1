<?php
    session_start();  
    
   include '../../includes/dbConnection.php';
    if(!isset($_SESSION['accountName']))
    {
        header('Location:index.php'); //sends user back to the login screen
    }
    
    
    
    
    $conn = getDatabaseConnection("project1");
    
    function displayCart()
    {
        global $conn;
        $shoppingCart = $_SESSION['shoppingCart'];
        $price = 0;
        
        $sql = "SELECT * FROM
              preorders p
              LEFT JOIN accounts a
                on p.accountId = a.accountId
              LEFT JOIN stock s 
                on s.gameId = p.gameId";
        $statement= $conn->prepare($sql); 
        $statement->execute(); 
        $records = $statement->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<div id ='middle'>";
         echo "<table style='margin: 0 auto'>";
        foreach($records as $preorder)
        {
            if($preorder ['accountName'] == $_SESSION['accountName'])
            {
                echo "<tr><td>";
                echo "<a href='viewTitle.php?gameTitle=".$preorder['title']. "'>" . $preorder['title'] . "</a>";
                echo "</td>";
                echo "</tr>";
                $price += getPrice($preorder['title']);
            }
        }
        foreach($shoppingCart as $game)
        {
            echo "<tr><td>";
            echo "<a href='viewTitle.php?gameTitle=".$game. "'>" . $game . "</a>";
            echo "</td>";
            echo "</tr>";
            $price += getPrice($game);
        }
        echo"<tr><td>Total Price: $" . $price . "</td></tr>";
        echo"</table>";
        echo "</div>";
    }
    
    function getPrice($game)
    {
        global $conn;
        $sql = "SELECT price FROM
              stock
              WHERE 
              title = :title";
        $namedParameters = array();
        $namedParameters[':title'] = $game;
        $statement= $conn->prepare($sql); 
        $statement->execute($namedParameters); 
        $records = $statement->fetch(PDO::FETCH_ASSOC);
               
        
        return $records['price'];
    
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Checkout</title>
        <link rel="stylesheet" href="css/proj1.css" type="text/css" />

    </head>
    <body>
        <h1>Checkout</h1>
        <br>
        <?=displayCart()?>
         <form class="back" action="shopping.php">
            <input type="submit" class="login" value="Back to Shopping"/>
        </form>
        
    </body>
</html>