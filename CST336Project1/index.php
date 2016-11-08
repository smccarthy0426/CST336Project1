<?php
    //lets put includes here
?>


<!DOCTYPE html>
<html>
    <head>
        <title>Gameshop</title>
        
        <link rel="stylesheet" href="../css/proj1.css" type="text/css" />
        
    </head>
    <body>
        <h1>Login to your Account!</h1>
        <form method="post" action = 'userLogin.php'>
            Username: <input type="text" name="accountName" /> <br />
            Password: <input type="password" name="password"  />
            <br />
            <input type="submit" name="loginForm" />
        </form>
    </body>
</html>