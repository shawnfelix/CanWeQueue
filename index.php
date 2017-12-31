<?php
    include 'functions.php';

    $rank = "master";
    $html = file_get_contents("template.html"); // opens template.html
    
    
    $servername = "107.180.43.16";
    $username = "owuser";
    $password = "Hanjoswap1!";
    $dbname = "OWSmurf";

    $connection = new mysqli($servername, $username, $password, $dbname);

    $accounts = loadFromCache($connection);

    $html = str_replace("{{accounts}}", $accounts, $html); 
    $html = str_replace("{{rank}}", $rank, $html); 
    echo $html;


?>