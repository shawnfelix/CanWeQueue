<?php
	include 'functions.php';

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
	function updateCache() {
        $connection = getDatabaseConnection();

        //get a list of all accounts to get from the OW api
        $query ="SELECT name, name_id FROM account WHERE active = 1";
        $result = mysqli_query($connection, $query);
        
        $accountDataArray = array();

        
        //for each account in the db
        while($row = mysqli_fetch_array($result)){
            $name = $row['name'];
            $nameId = $row['name_id'];

            //query the overwatch api
            $url = "http://owapi.net/api/v3/u/" . $name . "-" . $nameId . "/stats";

            $ch = curl_init();
            // Will return the response, if false it print the response
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
			// Disable SSL verification
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_VERBOSE, true);
			curl_setopt($ch,CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
            $apiResult = curl_exec($ch);


            $jsonData = json_decode($apiResult, true);

            $rank = $jsonData['us']['stats']['competitive']['overall_stats']['comprank'];

            if($rank > 0 && $rank < 5000){
                //generate update query
                $query = "UPDATE account SET sr='" . $rank . "' WHERE name='" . $name . "';";
                echo $query;
                mysqli_query($connection, $query);

                $accountDetail = array("name" => $name, "rank" => $rank);

                //add to payload to send to client
                array_push($accountDataArray, $accountDetail);
            } else {
                exit("Error getting data from OW api");
            }
        }
        //encode array and return to client as json
        echo json_encode($accountDataArray);
    }



	if (isset($_POST['apiRefresh'])) {
        return updateCache();
    } 
    else if(isset($_POST['test'])){
    	echo "works!";
    }
?>