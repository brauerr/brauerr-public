<?php
    //multtable.php
    ini_set('display-errors', '1');
    error_reporting('E_ALL');
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Multiplication Table</title>
    </head>
  
    <body>
        <?php
            /**  some documentation
            @category
            @package
            @author
            @license
            @link
            @URL  https://web.engr.oregonstate.edu/~brauerr/Assignment5/src/multtable.php?max-multiplicand=9&min-multiplicand=1&max-multiplier=9&min-multiplier=1
            */
        
            $minMultiplicand = $_GET['min-multiplicand'];
            $maxMultiplicand = $_GET['max-multiplicand'];
            $minMultiplier = $_GET['min-muliplier'];
            $maxMultiplier = $_GET['max-multiplier'];
            $width = 2 + $maxMultiplier - $minMultiplier;
            $height = 2 + $maxMultiplicand - $minMultiplicand;
            
            //check to ensure max values are larger than min values
            //check to ensure all values are populated and are integers
            //create multiplication table and output to screen in valid html5

            echo $minMultiplicand;
            echo $maxMultiplicand;
        
            echo '<p>Hello World</p>';
            echo $_SERVER['HTTP_USER_AGENT'];
        ?>
    
    </body>
</html>