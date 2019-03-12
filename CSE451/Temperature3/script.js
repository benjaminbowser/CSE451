//Benjamin Bowser
// Week5 - Rest Server
// 2/25/19

// Takes in a string of a city and retruns a string of lat/long
function temperature(city) {
	switch (city) {
		case "Oxford":
			lat = 39.5104;
			long = -84.7423;
			return (lat + ", " + long);
			break;
		case "International Falls":
			lat = 48.6027;
			long = -93.4022;
			return (lat + ", " + long);
			break;

		case "San Diego":
			lat = 32.7157;
			long = -117.1611;
			return (lat + ", " + long);
			break;

		case "New York":
			lat = 40.7123;
			long = -74.0078;
			return (lat + ", " + long);
			break;

		case "Dallas":
			lat = 32.7775;
			long = -96.7976;
			return (lat + ", " + long);
			break;
	}
}

// Post back to index with information from API for logging purposes
function log(data) {
	data = JSON.stringify(data);
	$.ajax({
		type: "POST",
		url: "index.php",
		data: data,
		dataType: "json"
	});
}

// API call to get temperature in a city, then set an h1 with data
function fetch(location) {
city = location;
location = temperature(location);

var data = { method: "POST", city: city };
log(data);

 $.ajax({
  url: "https://bowserbl.451.csi.miamioh.edu/cse451-bowserbl-web/Temperature2/api.php/api/v1/temp",
  type: "POST",
  data: JSON.stringify({location: location}),
  dataType: "json",
  success: function(data) {
	$("#data").empty();
	$("#data").append("The temperature in " + city + " at " +new Date(data.value.updateTime*1000) + " is " + data.value.temp + " degrees.<br>");
  },
  error: function(xhr) {
	alert("Something went wrong.");
},
});
}
// Removes a cached entry for a city then sets an h2
function remove(location) {
city = location;
location = temperature(location);

var data = { method: "DELETE", city: city };
log(data);

  $.ajax({
  url: "https://bowserbl.451.csi.miamioh.edu/cse451-bowserbl-web/Temperature2/api.php/api/v1/temp",
  type: "DELETE",
  data: JSON.stringify({location : location}),
  dataType: "json",
  success: function(data) {
	$("#data2").empty();
	$("#data2").append("Cleared entry.");
  },
  error: function(xhr) {
	alert("Something went wrong.");
},
});
}
$(document).ready(function() {
$("form").submit(function(event) {
	event.preventDefault();
	data = $("#selection").val();
	fetch(data);
});

$("#form2").submit(function(event) {
	event.preventDefault();
	data = $("#selection2").val();
	remove(data);
});
});
