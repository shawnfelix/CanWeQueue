function setAccountDetails(accountName){
	getJSONData(loadData);
}

function getJSONData(callback){
	var url = "https://owapi.net/api/v3/u/Phelix-11938/stats";
	
	$.getJSON(url, function(data){
		if(typeof callback === "function") {
        	callback(data);
    	}
	})
}

function loadData(data){
	var rank = data['us']['stats']['competitive']['overall_stats']['comprank'];
	$(" .rank-field").html(rank);
}