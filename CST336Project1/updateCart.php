<?php
    session_start();  
    
    include '../../includes/dbConnection.php';
    if(!isset($_SESSION['accountName']))
    {
        header('Location:index.php'); //sends user back to the login screen
    }
    
    function displayCart()
    {
        $count == 1;
        $shoppingCart = $_SESSION['shoppingCart'];
        $shoppingCart[] = $_GET['title'];
        echo "<table>";
        foreach($shoppingCart as $game)
        {
            echo "<tr><td>";
            echo "$count<a href='viewTitle.php?gameTitle=".$game. "'>". $game . "</a>";
            echo "</td>";
            echo "</tr>";
            $count++;
        }
        echo"</table>";
        $_SESSION['shoppingCart'] = $shoppingCart;
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Your Shopping Cart</title>
        <link rel="stylesheet" href="../css/proj1.css" type="text/css" />

    </head>
    <body>
        <h1>Current Contents of Shopping Cart</h1>
        
        <?=displayCart()?>
    </body>
</html>