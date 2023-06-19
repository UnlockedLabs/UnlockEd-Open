<?php


    $quoteArray = file("quotes/allQuotes");
    //print_r($quoteArray);
    $quoteLines = count($quoteArray);



/*
        for ($s=0;$s<$quoteLines;$s++)
        {
            if (stristr($quoteArray[$s], "--")){
                echo "<br>" . $quoteArray[$s] . "<br>";
            } elseif (stristr($quoteArray[$s], "~")){
                echo "<br>";
            } else {
                echo $quoteArray[$s];
            }
        }
        */



        /*
        for ($s=0;$s<$quoteLines;$s++)
        {
            if (stristr($quoteArray[$s], "--")){
                echo '<br>"author": "' . $quoteArray[$s] . '"<br><br>';
            } elseif (stristr($quoteArray[$s], "~")){
                echo "<br>";
            } else {
                echo '"quote": "' . $quoteArray[$s] . '",';
            }
        }
        */
  
  
  
        $counter = array("start");

        echo "{<br>";
        
        for ($s=0;$s<$quoteLines;$s++)
        {
            
            if (stristr($quoteArray[$s], "--")){
                echo $quoteArray[$s] . '},<br>';
                array_push($counter, $s);
            } elseif (stristr($quoteArray[$s], 'quote":')) {
                echo '"' . count($counter) . '": ';
                echo "{" . $quoteArray[$s];
            }

        }
        echo "<br>}";
  

?>