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
         echo "<br><br>";
        
        if($records['genre'] == 'RPG')
        {
            echo "<div style='background:rgba(255, 51, 51, 0.9); width: 1000px; height: 400px; '";
        }
        else if($records['genre'] == 'Adventure')
        {
            echo "<div style='background:rgba(204, 153, 0, 0.9); width: 1000px; height: 400px; '";
        }
        else if($records['genre'] == 'Horror')
        {
            echo "<div style='background:rgba(184, 77, 255, 0.9); width: 1000px; height: 400px; '";
        }
        else if($records['genre'] == 'RTS')
        {
            echo "<div style='background:rgba(51, 102, 204, 0.9); width: 1000px; height: 400px; '";
        }
        else if($records['genre'] == 'Action')
        {
            echo "<div style='background:rgba(153, 255, 153, 0.9); width: 1000px; height: 400px; '";
        }
        else if($records['genre'] == 'Platformer')
        {
            echo "<div style='background:rgba(255, 102, 204, 0.9); width: 1000px; height: 400px; '";
        }
        else if($records['genre'] == 'Simulator')
        {
            echo "<div style='background:rgba(0, 255, 0, 0.9); width: 1000px; height: 400px; '";
        }
        else if($records['genre'] == 'Racing')
        {
            echo "<div style='background:rgba(51, 51, 0, 0.9); width: 1000px; height: 400px; '";
        }
        else 
        {
            echo "<div>";
        }
        
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
        
        echo "</div>";
        
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Game Description</title>
    </head>
         <link rel="stylesheet" href="css/proj1.css" type="text/css" />
          <div id="body">
    <body>
        <?=displayInfo()?>
        
        <form class="back" action="shopping.php">
            <input type="submit" class="login" value="Back to Shopping"/>
        </form>
        
    </body>
    </div>
</html>