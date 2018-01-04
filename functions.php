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

    function getAccountInfoJson($name, $nameId){
        //query the overwatch api
        $url = "http://owapi.net/api/v3/u/" . $name . "-" . $nameId . "/stats";

        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        // Disable SSL verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch,CURLOPT_FOLLOWLOCATION, true);
        // owapi server rejects requests without a useragent
        curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
        $apiResult = curl_exec($ch);


        $jsonData = json_decode($apiResult, true);

        return $jsonData;
    }

    function getAccountSr($name, $nameId){
        $jsonData = getAccountInfoJson($name, $nameId);

        $sr = $jsonData['us']['stats']['competitive']['overall_stats']['comprank'];

        return $sr;
    }

?>