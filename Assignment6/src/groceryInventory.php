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
    } 

    //process POST info here - one of 4 things
    
    //****** ONE - add a product
    if (isset($_POST['addProduct'])) {
      //verify data format from POST and assign to variables
      $name = $_POST['productName']; //unique, required, and less than 255 characters
      $category = $_POST['productCategory']; //less than 255 characters
      $price = $_POST['productPrice']; //convertable to decimal format
      $dataOk = true;
      if (!is_numeric($price)) {
        echo "Price value submitted is not numeric".PHP_EOL;
        $dataOk = false;
      }
      if (!(strlen($name) <= 255)) {
        echo "Name of product is longer than 255 characters".PHP_EOL;
        $dataOk = false;
      }
      if (!(strlen($category) <= 255)) {
        echo "Category of product is longer than 255 characters".PHP_EOL;
        $dataOk = false;
      }
      if ($name == "") {
        echo "Name of product is required".PHP_EOL;
        $dataOk = false;
      }
      if ((double)($price) > 999.99) {
        echo "Price of product is greater than $999.99".PHP_EOL;
        $dataOk = false;
      }
      
      //prepared statement to add data
      if ($dataOk) {
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
    }
    
    //******* THREE - alter price of a category
    if (isset($_POST['alterPrices'])) {
      $percentAdjust = $_POST['alterPercent'];
      $category = $_POST['productCategory'];
      $dataOk = true;
      $productId= 0;
      $productPrice= 0;
      $newPrice = 0;
      
      if (!is_numeric($percentAdjust)) {
        echo "Percent to adjust price for ${category} must be numeric";
        $dataOk = false;
      }
      
      if ($dataOk) {
        if (!($stmt = $myConnection->prepare("SELECT id, price FROM grocery_inventory WHERE category = ?"))) {
          echo "Prepare failed: (" . $myConnection->errno . ") " . $myConnection->error;
        }
        if (!$stmt->bind_param("s", $category)) {
          echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }
        if (!$stmt->execute()) {
          echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }
        if (!$stmt->bind_result($productId, $productPrice)) {
          echo "Binding results failed: (" . $stmt->errno . ") " . $stmt->error;
        }
        $stmt->store_result();
        while ($stmt->fetch()) {
          $newPrice = $productPrice * $percentAdjust / 100;
          if (!($stmt2 = $myConnection->prepare("UPDATE grocery_inventory SET price = ? WHERE id = ?"))) {
            echo "Prepare failed: (" . $myConnection->errno . ") " . $myConnection->error;
          }
          if (!$stmt2->bind_param("ds", $newPrice, $productId)) {
            echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
          }
          if (!$stmt2->execute()) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
          }
          $stmt2->close();
        }
        $stmt->close();
      }
    }

    //******* FOUR - delete all products
    if (isset($_POST['deleteAllProducts'])) {
      $myConnection->query("TRUNCATE TABLE grocery_inventory");
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
        
          $outName = "";
          $outCategory = "";
          $outPrice = "";
          $id = 0;
          
          //pull data from table
          //display results with delete form with value tied to id
          if (!($stmt = $myConnection->prepare("SELECT id, name, category, price FROM grocery_inventory"))) {
            echo "Prepare failed: (" . $myConnection->errno . ") " . $myConnection->error;
          }
          if (!$stmt->execute()) {
          echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
          }
          if (!$stmt->bind_result($id, $outName, $outCategory, $outPrice)) {
            echo "Binding results failed: (" . $stmt->errno . ") " . $stmt->error;
          }
          while ($stmt->fetch()) {
            echo "<tr>
            <td>$outName
            <td>$outCategory
            <td>$outPrice
            <td>
              <form action=\"groceryInventory.php\" method=\"POST\">
                <input type=\"hidden\" name=\"rowToDelete\" value=\"${id}\">
                <input type=\"submit\" name=\"deleteProduct\" value=\"Delete\">
              </form>";
          }
          $stmt->close();
        ?>
        
      </table>
    </fieldset>
    
    <fieldset>
      <legend>Adjust Price of Inventory Category</legend>
      <form action="groceryInventory.php" method="POST">
    
        <?php
          //alter prices of all products in a category
          //query all products in a category
          //Example taken from:
          //http://stackoverflow.com/questions/8571902/mysql-select-only-unique-values-from-a-column
          
          //set loop equal to number of rows returned and populate drop down
          $category = "";
          
          echo "Product Category:
          <select name=\"productCategory\">";
          
          if (!($stmt = $myConnection->prepare("SELECT DISTINCT category FROM grocery_inventory ORDER BY category"))) {
            echo "Prepare failed: (" . $myConnection->errno . ") " . $myConnection->error;
          }
          if (!$stmt->execute()) {
          echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
          }
          if (!$stmt->bind_result($category)) {
            echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
          }
                    
          while($stmt->fetch()) {
            echo "<option value=\"${category}\">${category}</option>";
          }
          
          echo "</select>
          <br>
          Percent to Adjust By: <input type=\"text\" name=\"alterPercent\">%
          <br>
          <input type=\"submit\" name=\"alterPrices\" value=\"Alter Prices\">
          <br>";
        ?>
        
      </form>
    </fieldset>
    
    <form action="groceryInventory.php" method="POST">
      <input type="submit" name="deleteAllProducts" value="Delete All Products">
    </form>
    
  </body>
</html

