<?php
	include 'functions.php';

	function getCacheInformation(){

	}

	function loadFromCache($connection){
        $query = "SELECT * FROM account WHERE active = '1' ORDER BY name ASC ";
        $result = mysqli_query($connection, $query);

        if (!$result) {
            printf("Error: %s\n", mysqli_error($con));
            exit();
        }
        $rawhtml = "";
        $index = 0;
        while($row = mysqli_fetch_array($result)){
            $name = $row['name'];
            $nameId = $row['name_id'];
            $sr = $row['sr']; //SR
            $rank = generateRankBracket($sr);

            $backgroundColorClass = "light-red";

            if($index%2 == 0){
                $backgroundColorClass = "light-grey";
            }

            $rawhtml .= '<div class="account-wrapper '. $backgroundColorClass .'"><div id="' . $name . '">
                    <div class="rank-icon-wrapper">
                        <div class="rank-icon '. $rank .'"></div>
                        <div class="rank-field">' . $sr . '</div>
                    </div>
                    <div class="stats-wrapper">
                        <div class="name-wrapper">
                            <span class="name-field">' . $name . '</span>
                            <span class="hashtag-field">#' . $nameId . '</span>
                        </div>
                    </div>
                    <div class="delete-account-btn"></div>
                </div>
                </div>';

            $index++;
        }

        return $rawhtml;
    }
	function updateCache() {
        $connection = getDatabaseConnection();

        //get a list of all accounts to get from the OW api
        $query ="SELECT name, name_id FROM account WHERE active = 1";
        $result = mysqli_query($connection, $query);
        
        $accountDataArray = [];
        
        //for each account in the db
        while($row = mysqli_fetch_array($result)){
            $name = $row['name'];
            $nameId = $row['name_id'];

            //OW api call
            $sr = getAccountSr($name, $nameId);

            if($sr > 0 && $sr < 5000){
            	$rank = generateRankBracket($sr);

            	//TODO this is pretty inefficient, requires (n) queries to database
                //generate update query
                $query = "UPDATE account SET sr='" . $sr . "' WHERE name='" . $name . "';";
                mysqli_query($connection, $query);

                $accountDetail = array("name" => $name, "rank" => $rank, "sr" => $sr);

                //add to payload to send to client
                array_push($accountDataArray, $accountDetail);
            } else {
                exit("Error getting data from OW api");
            }
        }
        //encode array and return to client as json
        $jsonResponse = json_encode($accountDataArray);
        echo $jsonResponse;
    }

    /**
    * Adds a given account to the database
    *
    *
    */
    function addAccount($name, $number){
    	$connection = getDatabaseConnection();

    	$sr = getAccountSr($name, $number);
    	$rank = generateRankBracket($sr);

    	$query = "INSERT INTO account (account_id, name, name_id, sr, active) VALUES(0, '" . $name . "', " . $number . ", " . $sr . ", 1)";
    	
    	mysqli_query($connection, $query);

    	$accountData = array ("name" => $name, "number" => $number, "sr" => $sr, "rank" => $rank);

    	$jsonResponse = json_encode($accountData);
    	echo $jsonResponse;
    }

    /**
    * Removes a given account from the database
    *
    *
    */
    function removeAccount($name){
        $connection = getDatabaseConnection();

        $query = "DELETE FROM account WHERE name = '" . $name . "';";

        mysqli_query($connection, $query);
    }






//api router
	if (isset($_POST['apiRefresh'])) {
        return updateCache();
    } 
    else if(isset($_POST['addAccount'])){
    	return addAccount($_POST['name'], $_POST['number']);
    }
    else if(isset($_POST['removeAccount'])){
    	return removeAccount($_POST['name']);
    }
?>