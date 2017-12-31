<?php

    function loadFromCache($connection){
        $query = "SELECT * FROM account WHERE active = '1' ORDER BY name DESC ";
        $result = mysqli_query($connection, $query);

        if (!$result) {
            printf("Error: %s\n", mysqli_error($con));
            exit();
        }
        $rawhtml = "";
        while($row = mysqli_fetch_array($result)){
            $name = $row['name'];
            $nameId = $row['name_id'];
            $sr = $row['sr']; //SR
            $rank = generateRankBracket($sr);

            $rawhtml .= '<div id="' . $name . '">
                    <div class="rank-icon-wrapper">
                        <div class="rank-icon '. $rank .'"></div>
                    </div>
                    <div class="stats-wrapper">
                        <div class="name-wrapper">
                            <span class="name-field">' . $name . '</span>
                            <span class="hashtag-field">#' . $nameId . '</span>
                        </div>
                        <div class="rank-wrapper">
                            Current Competitive Rank:
                            <span class="rank-field">' . $sr . '</span>
                        </div>
                    </div>
                </div>';
        }

        return $rawhtml;
    }

    function generateRankBracket($rank){
        switch($rank){
            case $rank > 0 && $rank < 1000 :
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
                return 'bronze';
        }
    }
    function updateCache($connection) {
        $query 

        $result = mysqli_query($connection, $query);
        

        // Check connection
        if ($connection->connect_error) {
            die("Connection failed: " . $connection->connect_error);
        } 
        echo "Connected successfully";
    }
?>