
<?php
    session_start();
    
    include '../../includes/dbConnection.php';
    if(!isset($_SESSION['accountName']))
    {
        header('Location:index.php'); //sends user back to the login screen
    }
    
    $conn = getDatabaseConnection("project1");
    $accountName = $_POST['accountName'];
    $password = $_POST['password'];  
    
    
    if (!isset($_SESSION['shoppingCart'])) {
        $shoppingCart = array();  //initializing session variable
        $_SESSION['shoppingCart'] = $shoppingCart;
     }
     
     
    $shoppingCart = $_SESSION['shoppingCart'];
    
    function genCurrentOrders()
    {
        global $conn, $shoppingCart;
        $sql = "SELECT * FROM
              preorders p
              LEFT JOIN accounts a
                on p.accountId = a.accountId
              LEFT JOIN stock s 
                on s.gameId = p.gameId";
        $statement= $conn->prepare($sql); 
        $statement->execute(); 
        $records = $statement->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<table>";
        foreach($records as $preorder)
        {
            if($preorder ['accountName'] == $_SESSION['accountName'])
            {
                echo "<tr>";
                echo "<td>" . $preorder['title'] . "</td>";
                echo "<td>" . $preorder['releaseDate'] . "</td>";
                if($preorder['paidOff'] == true)
                {
                    echo "<td>Paid</td>";
                }
                else {
                    echo "<td id = 'notPaid'>Not Paid Off</td>";
                }
                echo "</tr>";
                
            }
        }
        echo "</table>";
        
        $_SESSION['shoppingCart'] = $shoppingCart;
    }
    
    function genListOfTitles()
    {
        global $conn,$shoppingCart;
        $sql = "SELECT * FROM
              stock";
        $statement= $conn->prepare($sql); 
        $statement->execute(); 
        $records = $statement->fetchAll(PDO::FETCH_ASSOC);
        
        
        
        
        echo "<table>";
        echo "<tr><td>Click a title to find out more!</td></tr>";
        foreach($records as $game)
        {
            if(!in_array($game['title'],$shoppingCart))
            {
                echo "<tr><td>";
                echo "<a href='viewTitle.php?gameTitle=".$game['title']. "'>" . $game['title'] . "</a>";
                echo "</td>";
                echo "<td>";
                echo "<a href='updateCart.php?title=".$game['title']."'>Add To Cart</a>";
                echo "</td>";
                echo "</tr>";
                
            }
        }
        echo"</table>";
        $_SESSION['shoppingCart'] = $shoppingCart;
    }
    
    function addToCart($title)
    {
        $shoppingCart = $_SESSION['shoppingCart'];
        $shoppingCart[] = $title;
        $_SESSION['shoppingCart'] = $shoppingCart;
        header('Location:viewCart.php'); //sends user back to the login screen
    }
?>


<!DOCTYPE html>
<html>
    <head>
        <title>Your Account</title>
        <link rel="stylesheet" href="css/proj1.css" type="text/css" />
    </head>
    <body>
        <h2>
            Current orders for <?=$_SESSION['currentUser']?> : <br />
        </h2>

            <?=genCurrentOrders()?>
        <form method = 'get'>
            <h2>
                Grab some more games today!
            </h2>
            <br />
            <?=genListOfTitles()?>
        </form>
        <form action="displayCart.php">
           <br />
           <input type="submit" value="Checkout">
         </form>  
    </body>
</html>