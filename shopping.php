
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
    
    
    function genCurrentOrders()
    {
        global $conn;
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
    
    </body>
</html>