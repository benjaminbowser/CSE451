// Benjamin Bowser
// CSE451
// 2/7/19
// Week 2
function getValues(key) {
$.ajax({
url: "http://campbest.451.csi.miamioh.edu/cse451-campbest-web-public/week2/week2-rest.php/api/v1/info",
type: "POST",
data: JSON.stringify({key: key}),
dataType: "json",
success: function(data) {
$("#table").append("<tr><td>" + key + "</td><td>" + data.value + "</td></tr>");
},
error: function( xhr ){
 alert("An error occured.");
},
});
}

function getKeys() {
  $.ajax({
  url: "http://campbest.451.csi.miamioh.edu/cse451-campbest-web-public/week2/week2-rest.php/api/v1/info",
  type: "GET",
  dataType: "json",
  success: function(reply) {
   for (var i = 0; i < reply.keys.length; i++) {
	getValues(reply.keys[i]);
   }
  },
  error: function( xhr ){
   alert("An error occured");
  }
  });
}

$(document).ready(function() {
	getKeys();
});

