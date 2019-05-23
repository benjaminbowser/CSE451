$(document).ready(function(){
	getComments();
	$("#submit").click(function() {
		var comment = $("#commentArea").val();
		addComment(comment);
	});
});

function getComments(){
	$.ajax({
		url: "http://ceclnx01.cec.miamioh.edu/~bowserbl/cse451-bowserbl-web/final2/rest.php/api/v1/Comments",
		type: "GET",
		dataType: "JSON",
		contentType: "application/json",
		success: function (text){
			var str = JSON.stringify(text);
			var obj = JSON.parse(str);

			var commentData = "";
			for (var i = 0; i < obj.Comments.length; i++) {
				commentData += "<tr><td>" + obj.Comments[i].pk + "</td><td>" + obj.Comments[i].comment + "</td><td>" + obj.Comments[i].createTime + "</td><td>" + "<button pk=" + obj.Comments[i].pk + " onclick='deleteEntry(" +obj.Comments[i].pk + ")'>" + "Delete</button>" + "</td></tr>";
			}
			//alert(commentData);
			$("#comments").html(commentData);
		},
		error: function( xhr ) {
	      		alert("Error getting comments");
		}
	});
}


function deleteEntry(id) {
	var pass = $("#pass").val();
	var obj = {};
	obj.password = pass;
	$.ajax({
                url: "http://ceclnx01.cec.miamioh.edu/~bowserbl/cse451-bowserbl-web/final2/rest.php/api/v1/Comment/" + id,
                type: "PUT",
                dataType: "JSON",
                contentType: "application/json",
                data: JSON.stringify(obj),
		success: function (text){
                        var str = JSON.stringify(text);
                        var obj = JSON.parse(str);
                        if (obj.msg == "FAIL") {
				alert("Incorrect password or entry does not exist");
			} else {
				getComments();
			}
                },
                error: function( xhr ) {
                        alert("Error deleting entry. Check password.");
                }
        });
}

function addComment(comment) {
	if (comment == "") {
		alert("You must enter a comment");
	} else {
	var pass = $("#pass").val();
        var obj = {};
        obj.password = pass;
        obj.comment = comment;
	$.ajax({
                url: "http://ceclnx01.cec.miamioh.edu/~bowserbl/cse451-bowserbl-web/final2/rest.php/api/v1/Comment",
                type: "PUT",
                dataType: "JSON",
                contentType: "application/json",
                data: JSON.stringify(obj),
                success: function (text){
                        var str = JSON.stringify(text);
                        var data = JSON.parse(str);
			if (obj.msg == "FAIL") {
				alert("Incorrect password");
			} else {
				getComments();
			}
               },
                error: function( xhr ) {
                        alert("Error adding entry. Check password.");
                	alert(xhr.responseText);
		}
        });
	}

}
