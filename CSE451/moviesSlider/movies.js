//scott campbell
// movies.js
//
var slider = 50;

$(document).ready(function() {
	data = $("#slider").val();
	$("#get").click(updateMovies(data));

	$("#slider").change(function() {
		slider = $("#slider").val();
		$("#val").html(slider);
		updateMovies(slider);
	});
});

function update(data) {
	console.log(data);
}

//call movies server and update table
function updateMovies(value) {
	$.ajax({
	url: "https://d6jk6pah5d.execute-api.us-east-1.amazonaws.com/default/%7Bproxy+%7D/audienceScore/" + value,
	type: "GET",
	dataType: "json",
	success: function(data) {
		$("#body").html("");
		for (var i = 0; i < data.movies.length; i++) {
			$("#body").append("<tr><td>" + data.movies[i].title + "</td><td>" + 
			data.movies[i].year + "</td><td>" + data.movies[i].gross + "</td><td>" + 
			data.movies[i].studio + "</td><td>" + data.movies[i].genre + "</td><td>" +
			data.movies[i].rottenScore + "</td><td>" + data.movies[i].audienceScore +
			"</td></tr>");
		}
	},
	error: function( xhr ){
	alert("An error occured.");
	},
	});
}
