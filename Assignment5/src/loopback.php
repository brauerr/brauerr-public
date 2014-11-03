<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Loopback Test</title>
    </head>
    
    <body>
        <?php
            /*
            This file should accept either a GET or POST for input. 
            That GET or POST will have an unknown number of key/value pairs. 
            The page should return a JSON object 
            (remember, this is almost identical to an object literal in JavaScript) 
            of the form 
            {"Type":"[GET|POST]",
            "parameters":{"key1":"value1", ... ,"keyn":"valuen"}}. 
            Behavior if a key is passed in and no value is specified is undefined. 
            If no key value pairs are passed it it should return 
            {"Type":"[GET|POST]", "parameters":null}. 
            You are welcome to use built in JSON function 
            //in PHP to produce this output.
            */
            
            ini_set('display-errors', '1');
            error_reporting('E_ALL');
            
            //example taken from 
            //stackoverflow.com/questions/15220704/how-to-detect-if-post-is-set
            //check to see if request was POST or GET
            
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                echo "{\"Type\":\"POST\", \"parameters\":";
              
                //check if no parameters were passed
                if (count($_POST) > 0) {
                    echo json_encode($_POST);
                } else {
                    echo "null";
                }
                echo "}";
            }
            
            if ($_SERVER['REQUEST_METHOD'] == 'GET') {
                echo "{\"Type\":\"GET\", \"parameters\":";
                
                //check if no parameters were passed
                if (count($_GET) > 0) {
                    echo json_encode($_GET);
                } else {
                    echo "null";
                }
                echo "}";
            }

        ?>

    </body>
</html>