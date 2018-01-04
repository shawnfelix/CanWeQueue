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

function apiAddNewAccount(accountName, accountNumber){

	$.ajax({
		type: 'POST',
		url: '/api.php',
		data: {"addAccount": "1", "name": accountName, "number": accountNumber},
		dataType: "json",
		success: function(jsonPayload){
			addAccountToView(jsonPayload);
		},
		error: function(xhr, textStatus, error){
			alert('fail');
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


function addAccountToView(jsonPayload){
    var template ='<div id="' 
    	+ jsonPayload.name + '" style="display:none;"><div class="rank-icon-wrapper"><div class="rank-icon '
    	+ jsonPayload.rank + '"></div></div><div class="stats-wrapper"><div class="name-wrapper"><span class="name-field">' 
    	+ jsonPayload.name + '</span><span class="hashtag-field">&nbsp;#' 
    	+ jsonPayload.number + '</span></div><div class="rank-wrapper">Current Competitive Rank:&nbsp;<span class="rank-field">'
    	+ jsonPayload.sr + '</span></div></div></div>';
    //append new account object
    $("#accounts-pane").append(template);
    $('#' + jsonPayload.name).slideToggle("slow");
}
function updateView(payload){
	for(i = 0; i < payload.length; i++){
		$("#" + payload[i].name + " .rank-field").html(payload[i].sr);
		$("#" + payload[i].name + " .rank-icon").removeClass().addClass("rank-icon " + payload[i].rank);
	}
}
