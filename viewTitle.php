<?php
    session_start();  
    include '../../includes/dbConnection.php';
    if(!isset($_SESSION['accountName']))
    {
        header('Location:index.php'); //sends user back to the login screen
    }
    
    function displayInfo()
    {
        $conn = getDatabaseConnection("project1");
        $sql = "SELECT * FROM
              stock
              WHERE 
              title = :title";
        $namedParameters = array();
        $namedParameters[':title'] = $_GET['gameTitle'];
        $statement= $conn->prepare($sql); 
        $statement->execute($namedParameters); 
        $records = $statement->fetch(PDO::FETCH_ASSOC);
        
        echo "<h1>";
        echo $records['title'];
        echo "</h1>";
        
        $title = $records['title'];
        
        $title = str_replace(' ', '', $records['title']);
        
        echo "Genre:" . $records['genre'] . "<br />";
        echo "Description : " . $records['description'] . "<br />";
        echo "<br />";
        echo "<br />";
        echo "<img src = 'img/" . strtolower($title) . ".jpg'/><br />";
        echo "Price: $" . $records['price'] . "<br />";
        
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Game Description</title>
    </head>
    <body>
        <?=displayInfo()?>
        
    </body>
</html>