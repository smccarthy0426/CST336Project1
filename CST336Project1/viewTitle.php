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
        
        echo "<div id='viewTitle'>";
        
        echo "<div id='image'>";
        echo "<img src = 'img/" . strtolower($title) . ".jpg'/><br />";
        echo "</div>";
        
        echo "<div id='text'>";
        echo "Genre: " . $records['genre'] . "<br />";
        echo "Description: " . $records['description'] . "<br />";
        echo "<br />";
        echo "Price: $" . $records['price'] . "<br />";
        echo "</div>";
        
        echo "</div>";
        
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Game Description</title>
    </head>
         <link rel="stylesheet" href="css/proj1.css" type="text/css" />
    <body>
        <?=displayInfo()?>
        
    </body>
</html>