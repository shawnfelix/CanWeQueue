<?php

    function getDatabaseConnection(){
        $servername = "107.180.43.16";
        $username = "owuser";
        $password = "Hanjoswap1!";
        $dbname = "OWSmurf";

        return new mysqli($servername, $username, $password, $dbname);
    }
    function generateRankBracket($rank){
        switch($rank){
            case $rank > 0 && $rank < 1500 :
                return 'bronze';
            break;
            case $rank >= 1500 && $rank < 2000 :
                return 'silver';
            break;
            case $rank >= 2000 && $rank < 2500 :
                return 'gold';
            break;
            case $rank >= 2500 && $rank < 3000 :
                return 'platinum';
            break;
            case $rank >= 3000 && $rank < 3500 :
                return 'diamond';
            break;
            case $rank >= 3500 && $rank < 4000 :
                return 'master';
            break;
            case $rank >= 4000 && $rank < 5000 :
                return 'grandmaster';
            break;
            default:
                return 'unranked';
        }
    }

?>