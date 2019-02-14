/*
   Scott Campbell
   cse451
   week2

   script to update table data
 */

function getValue(key) {
	return new Promise((resolv,reject) => {
	$.ajax({
url:'http://campbest.451.csi.miamioh.edu/cse451-campbest-web-public/week2/week2-rest.php/api/v1/info',
method: 'post',
data: JSON.stringify({key:key}),
dataType: 'json',
headers: {"Content-Type":"application/json"},
success: function(result) {
	console.log("getValue",result);

		var d = [];
		d[0] = key;
		d[1] = result.value;
		resolv(d);
		}
});
})
}

function getKeys() {
	$("#info-body").html("");
	//send jquery
	$.ajax({
url:'http://campbest.451.csi.miamioh.edu/cse451-campbest-web-public/week2/week2-rest.php/api/v1/info',
method: 'get',
dataType: 'json',
success: function(result) {
console.log(result);
for (var i=0,l=result.keys.length;i<l;i++) {
	getValue(result.keys[i]).then((data) => {
		$("#info-body").append("<tr><td>" + data[0] + "</td><td>" + data[1] + "</td></tr>");
	});
}
},

error: function(err) {

$("#msg").html("error " + err);
console.log(err);
}
})
}


$(document).ready(function() {
		getKeys();	
		$("#refresh").click(getKeys);

		});


