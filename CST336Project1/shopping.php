
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
        echo " <col width='700'>";
        echo "<tr>";
        foreach($records as $game)
        {
            $title = $game['title'];
        
            $title = str_replace(' ', '', $game['title']);
            
             
            
            if(!in_array($game['title'],$shoppingCart))
            {
                echo "<td>";
                echo "<img src = 'img/" . strtolower($title) . ".jpg'/><br />";
                echo "<a href='viewTitle.php?gameTitle=".$game['title']. "'>" . $game['title'] . "</a>";
                echo "</td>";
                echo "<td>";
                echo "<a href='updateCart.php?title=".$game['title']."'>Add To Cart</a>";
                echo "</td> </tr>";
                
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
        function getgenres()
    {
        global $conn;
        $sql = "SELECT DISTINCT genre
                FROM stock ";
        $statement = $conn->prepare($sql);
        $statement->execute();
        $records = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $records;
    }
   
    function filterList()
    {
        global $conn, $shoppingCart;
        $price = $_GET['price'];
        $date = $_GET['date'];
        
        $namedParameters = array();
        $orders = array();
        if (!empty($_GET['title']))
        {
            $sql = "SELECT * FROM stock
                    WHERE title LIKE :title";
            $namedParameters[':title']= "%". $_GET['title'] . "%";
        }
        else{
            $sql = "SELECT * FROM stock ";
        }
        if (!empty($_GET['genre']))
        {
            if (!empty($_GET['title']))
            {
                $sql = $sql . " AND genre =  :genre ";
            }
            else {
                $sql = $sql . " WHERE genre =  :genre ";
            }
            $namedParameters[':genre'] = $_GET['genre'];
        }
        if (!empty($_GET['price'])  && empty($_GET['date']))
        {
            if ($price == "ASC")
                $sql = $sql . " ORDER BY price ASC " ;
            else 
                $sql = $sql . " ORDER BY price DESC ";
                
        }
        if (!empty($_GET['date']) && empty($_GET['price']))
        {
            if ($date == "ASC")
                $sql = $sql . " ORDER BY releaseDate ASC";
            else 
                $sql = $sql . " ORDER BY releaseDate DESC ";
                
        }
        if (!empty($_GET['date']) && !empty($_GET['price']))
        {
            $orders[':price'] = $_GET['price'];
            $orders[':date'] = $_GET['date'];
            $sql = $sql . " ORDER BY price " . $orders[':price'] . ", releaseDate " . $orders[':date'] . " ";
        }

        $statement= $conn->prepare($sql); 
        $statement->execute($namedParameters); 
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
    function check()
    {
        if(isset($_GET['filter'])){
            //echo "Form was submitted!";
            filterList();
        }
        else {
            genListOfTitles();
        }
}

?>


<!DOCTYPE html>
<html>
    <head>
        <title>Your Account</title>
        <link rel="stylesheet" href="css/proj1.css" type="text/css" />
    </head>
    <div id="body">
    <body>
        <h2>
            Current orders for <?=$_SESSION['currentUser']?> : <br />
        </h2>

            <?=genCurrentOrders()?>
        <form method = 'get'>
            <h2>
                Grab some more games today!
            </h2>
            <br>
             <h2>Click a title to find out more! </h2>
            <br/>
            Search: 
            <input type="text" name="title" placeholder="Game Title">
            <br/>
            Genre:
            <select name="genre" >
                 <option value=""> Select A Genre </option>
                   <?php
                   $genres = getgenres();
                   foreach ($genres as $genre) {
                       echo "<option value='" . $genre['genre'] . "'> " . $genre['genre']. " </option>";
                   }
                   ?>
                   
            </select>
               Price: 
               <select name="price">
                   <option value="">Order By</option>
                   <option value="ASC">Low to High</option>
                   <option value="DESC">High to Low</option>
               </select>
               Release Date:
               <select name="date">
                   <option value="">Order By</option>
                   <option value="ASC">Soonest</option>
                   <option value="DESC">Latest</option>
               </select>
               <br/>
               <input type="submit" name = "filter" value="Filter">
               <input type="submit" name = "cfilter" value="Clear">
               <br/>
               <?php
                    check();
               ?>
        </form>
        <form action="displayCart.php">
           <br />
           <input type="submit" value="Checkout">
         </form>  
    </body>

    </div>
</html>