<?php
    ini_set('display-errors', '1');
    error_reporting('E_ALL');
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Multiplication Table</title>
        <link rel="stylesheet" href="assgn5Style.css">
    </head>
  
    <body>
        <?php
            //Test URL with GET values:  
            //https://web.engr.oregonstate.edu/~brauerr/Assignment5/src/multtable.php?max-multiplicand=9&min-multiplicand=1&max-multiplier=9&min-multiplier=1
        
            //set boundary values from GET superglobal array
            $minMultiplicand = (int)$_GET['min-multiplicand'];
            $maxMultiplicand = (int)$_GET['max-multiplicand'];
            $minMultiplier = (int)$_GET['min-multiplier'];
            $maxMultiplier = (int)$_GET['max-multiplier'];
            $getValuesOk = true;
            
            //check to ensure max values are larger than min values
            if ($maxMultiplicand < $minMultiplicand) {
                echo "Minimum multiplicand larger than maximum<br>";
                $getValuesOk = false;
            }
            if ($maxMultiplier < $minMultiplier) {
                echo "Minimum multiplier larger than maximum<br>";
                $getValuesOk = false;
            }
            
            //check to ensure that all values are populated
            if ($maxMultiplier == null) {
                echo "Missing parameter max multiplier<br>";
                $getValuesOk = false;
            }            
            if ($minMultiplier == null) {
                echo "Missing parameter min multiplier<br>";
                $getValuesOk = false;
            } 
            if ($maxMultiplicand == null) {
                echo "Missing parameter max multiplicand<br>";
                $getValuesOk = false;
            }
            if ($minMultiplicand == null ) {
                echo "Missing parameter min mulitplicand<br>";
                $getValuesOk = false;
            }
            
            //check to ensure that all values are integers
            if (!is_int($minMultiplicand)) {
                echo "Min multiplicand must be an integer<br>";
                $getValuesOk = false;
            }
            if (!is_int($maxMultiplicand)) {
                echo "Max multiplicand must be an integer<br>";
                $getValuesOk = false;
            }
            if (!is_int($minMultiplier)) {
                echo "Min multiplier must be an integer<br>";
                $getValuesOk = false;
            }
            if (!is_int($maxMultiplier)) {
                echo "Max multiplier must be an integer<br>";
                $getValuesOk = false;
            }

            //if all checks pass, call function printMultTable
            if ($getValuesOk) {
                printMultTable($minMultiplicand, $maxMultiplicand, $minMultiplier, $maxMultiplier);
            }
            
            function printMultTable($minMultiplicand, $maxMultiplicand, $minMultiplier, $maxMultiplier)
            {
                //set width and height of table
                $width = 2 + $maxMultiplier - $minMultiplier;
                $height = 2 + $maxMultiplicand - $minMultiplicand;
                $i = 0;
                $j = 0;
                $value = 0;
                
                //create table embedded html
                echo '<table>
                <caption>Multiplication Table</caption>
                <tbody>
                <tr>
                <th>';
                
                //create header row
                for ($j = 0; $j < $width - 1; $j++) {
                  $value = $minMultiplier + $j;
                  echo "<th>$value";
                }
                
                //populate additional rows
                for ($i = 0; $i < $height - 1; $i++) {
                    echo '<tr>';
                    //print multiplicand value in first column
                    $value = $minMultiplicand + $i;
                    echo "<td>$value";
                    
                    for ($j = 0; $j < $width - 1; $j++) {
                        $value = ($minMultiplier + $j) * ($minMultiplicand + $i);
                        echo "<td>$value";
                    }
                }
                
                //close html table tags
                echo '</tbody>
                </table>';
            }
        ?>
    
    </body>
</html>