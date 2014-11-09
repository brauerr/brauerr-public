<?php
ini_set('display_errors', 'On');
include 'storedInfo.php'; //for storing password and other secure data
?>

<!DOCTYPE html>
<html>
  <head>
     <meta charset='utf-8'>
     <title>Grocery Inventory</title>
     <link rel="stylesheet" href="assign6Style.css">
  </head>

    <?php
    $myConnection = new mysqli("oniddb.cws.oregonstate.edu", "brauerr-db", $myPassword, "brauerr-db");
    if ($myConnection->connect_errno) {
      echo "Failed to connect to MySQL: (" . $myConnection->connect_errno . ") " . $myConnection->connect_error;
    } else {
      echo "Connection worked!".PHP_EOL;
    }

    //process GET info here - one of 4 things
    
    //****** ONE - add a product
    if (isset($_POST['addProduct'])) {
      //verify data format from POST and assign to variables
      $name = $_POST['productName']; //unique and less than 255 characters
      $category = $_POST['productCategory']; //less than 255 characters
      $price = $_POST['productPrice']; //convertable to decimal format
      
      //prepared statement to add data
      if (!($stmt = $myConnection->prepare("INSERT INTO grocery_inventory(name, category, price) VALUES (?,?,?)"))) {
        echo "Prepare failed: (" . $myConnection->errno . ") " . $myConnection->error;
      }
      if (!$stmt->bind_param("ssd", $name, $category, $price)) {
        echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
      }
      if (!$stmt->execute()) {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
      }
      $stmt->close(); //explicitly close statement
    }

    //******* TWO - delete a single product from table
    if (isset($_POST['deleteProduct'])) {
      $deleteId = $_POST['rowToDelete'];
      
      //prepared statement to delete row
      if (!($stmt = $myConnection->prepare("DELETE FROM grocery_inventory WHERE id = ?"))) {
        echo "Prepare failed: (" . $myConnection->errno . ") " . $myConnection->error;
      }
      if (!$stmt->bind_param("i", $deleteId)) {
        echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
      }
      if (!$stmt->execute()) {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
      }
      $stmt->close();
      
      /*mysqli_query($myConnection, "DELETE FROM grocery_inventory
                                   WHERE id = ". $_POST['rowToDelete']);*/
    }
    
    //******* THREE - alter price of a category
    if (isset($_POST['alterPrice'])) {

    }

    //******* FOUR - delete all products
    if (isset($_POST['deleteAllProducts'])) {

    }

    ?>
  
  <body>
    <fieldset>
      <legend>Add a Product</legend>
      <form action="groceryInventory.php" method="POST">
        Name:
        <input type="text" name="productName">
        <br>
        Category: 
        <input type="text" name="productCategory">
        <br>
        Price: $
        <input type="text" name="productPrice">
        <br>
        <input type="submit" value="Add Product" name="addProduct">
      </form>
    </fieldset>
    
    <fieldset>
      <legend>Table of Current Products</legend>
      <table name="productTable">
        <thead>
          <tr>
            <th>Product Name
            <th>Product Category
            <th>Product Price
            <th>Delete Product
        </thead>
      
        <?php
          //Used as reference for creating delete buttons
          //http://stackoverflow.com/questions/16293741/original-purpose-of-input-type-hidden
        
          //display table of current products
          //create table, 4 columns - name, category, price, and delete button
          $i = 1;
          $numProducts = 3;
          $showName = "carrot";
          $showCategory = "vegetable";
          $showPrice = "1.29";
          $id = 9;
          
          //figure out size of table and set up loop
          
          
          //query every row in table and display results - creating delete button each time
          for ($i = 1; $i <= $numProducts; $i++) {
            //set id variable
            echo "<tr>
            <td>$showName
            <td>$showCategory
            <td>$showPrice
            <td>
              <form action=\"groceryInventory.php\" method=\"POST\">
                <input type=\"hidden\" name=\"rowToDelete\" value=\"${id}\">
                <input type=\"submit\" name=\"deleteProduct\" value=\"Delete\">
              </form>";
          }
        ?>
        
      </table>
    </fieldset>
    
    <fieldset>
      <legend>Adjust Price of Inventory Category</legend>
      <form action="groceryInventory.php">
    
        <?php
          //alter prices of all products in a category
          //query all products in a category
          
          
          //set loop equal to number of rows returned and populate drop down
          $i = 1;
          $numProducts = 3;
          $category = "abc";
          
          echo "Product Category:
          <select id=\"productCategory\">";
                    
          for ($i = 0; $i < $numProducts; $i++) {
            echo "<option value=\"${category}\">${category}</option>";
          }
          
          echo "</select>
          <br>
          Percent to Adjust By: <input type=\"number\" name=\"alterPercent\">
          <br>
          <input type=\"button\" name=\"alterPrices\" value=\"Alter Prices\" action=\"\">
          <br>";
        ?>
        
      </form>
    </fieldset>
    
    <form action="groceryInventory.php" method="POST">
      <input type="submit" name="deleteAllProducts" value="Delete All Products">
    </form>
    
  </body>
</html

