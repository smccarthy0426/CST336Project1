<?php
    //lets put includes here
?>


<!DOCTYPE html>
<html>
    <head>
        <title>Gameshop</title>
        
        <link rel="stylesheet" href="css/proj1.css" type="text/css" />
    </head>
    <body>
        <div id="wrapper">
        <h1>Login to your Account!</h1>
        <br>
        <form method="post" action = 'userLogin.php'>
            Username: <input type="text" name="accountName" /> <br />
            Password: <input type="password" name="password"  />
            <br />
            <input class ="submit" type="submit" name="loginForm" />
        </form>
        </div>
    </body>
</html>