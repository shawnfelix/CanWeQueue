<?php
    include 'api.php';

    $rank = "master";
    $html = file_get_contents("template.html"); // opens template.html
    
    
    $connection = getDatabaseConnection();

    $accounts = loadFromCache($connection);

    $html = str_replace("{{accounts}}", $accounts, $html); 
    $html = str_replace("{{rank}}", $rank, $html); 
    echo $html;


?>