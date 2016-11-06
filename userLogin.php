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
    
    $sql = "SELECT * FROM
              accounts 
              WHERE accountName = :accountName
              AND password = :password";
                    
    $namedParameters = array();
    $namedParameters[':accountName']= $accountName;
    $namedParameters[':password'] = $password;
    $statement = $conn->prepare($sql);
    $statement->execute($namedParameters);
    $record = $statement->fetch(PDO::FETCH_ASSOC);
   
    if (empty($record)) {  //it didn't find any record
        
        echo "This username does not exist in our records. Please try again or create a new account";
        echo "<a href='login.php'> Try again </a>";
        
    } else {
        
        $_SESSION['accountName'] = $record['accountName'];
        $_SESSION['currentUser'] = $record['firstName'] . " " . $record['lastName'];
        echo "Success";
        //header('Location: shopping.php');  //redirects to another program        
        
    }
              
    
    
    
    
    
    
    
    

?>