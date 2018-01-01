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

function addNewAccount(accountName, accountNumber){
	//var account.accountName = accountName;
	//var account.accountNumber = accountNumber;

	$.ajax({
		type: 'POST',
		url: '/api.php',
		data: {service: JSON.stringify(accountName)},
		dataType: "json",
		success: function(){
			//update();
		}
	});
}

function apiRefresh(){
	$.ajax({
		type: 'POST',
		url: '/api.php',
		data: {"apiRefresh": "1"},
		dataType: "json",
		success: function(jsonPayload){
			updateView(jsonPayload);
		},
		error: function(xhr, textStatus, error){
			var data = xhr.responseText;

    		$('body').replaceWith(data);
		}
	});
}

function updateView(payload){
	for(i = 0; i < payload.length; i++){
		$("#" + payload[i].name + " .rank-field").html(payload[i].sr);
		$("#" + payload[i].name + " .rank-icon").removeClass().addClass("rank-icon " + payload[i].rank);
	}
}