/*
   Scott Campbell
   Final project template
   CSE451
   Spring 2019
   */ 'use strict'; const Alexa = require('alexa-sdk'); //Replace with 
your app ID (OPTIONAL).  You can find this value at the top of your 
skill's page on http://developer.amazon.com. //Make sure to enclose your 
value in quotes, like this: const APP_ID = 
'amzn1.ask.skill.bb4045e6-b3e8-4133-b650-72923c5980f1'; const APP_ID = 
undefined; const SKILL_NAME = 'Campbell Final Project'; const 
HELP_MESSAGE = 'You can say please read list or please add comment?'; 
const HELP_REPROMPT = 'What can I help you with?'; const STOP_MESSAGE = 
'Goodbye!'; function startOAUTH(oldThis) {
	let userID = oldThis.event.session.user.userId.split(".");
	let registerText = "Please register at: 
http://ceclnx01.cec.miamioh.edu/~bowserbl/cse451-bowserbl-web/AlexaAuth/index.php?USER=" 
+ userID[3];
	oldThis.response.cardRenderer(SKILL_NAME, registerText);
	oldThis.response.speak(registerText);
	oldThis.emit(':responseReady'); // DELETE
	oldThis.attributes['token'] = -1;	//I store this so update 
can work
	// FOR STUDENTS TO DO
	
}
//code to call todoist and get list items function 
getList(token,oldThis) {
    console.log("getList",token);
    
	if (token =="") {
	    console.log("error - empty token");
		oldThis.response.speak("Error - getlist called with null 
token");
		oldThis.emit(':responseReady');
		return;
	}
	var speech = "";
	var http = require("https");
	var options = {
		"method": "GET",
		"hostname": "beta.todoist.com",
		"port": null,
		"path": "/API/v8/tasks",
		"headers": {
			"authorization": "Bearer "+ token,
		}
	};
	//make request
	var req = http.request(options, function (res) {
		var chunks = [];
		res.on("data", function (chunk) {
			chunks.push(chunk);
		});
		res.on("end", function () {
			try {
				var body = Buffer.concat(chunks);
				var inJson = JSON.parse( 
body.toString());
				speech = inJson[0].content;
				oldThis.response.speak("The first task 
is " + speech);
				oldThis.response.cardRenderer(SKILL_NAME, 
speech);
				oldThis.emit(':responseReady');
			} catch (error) {
				oldThis.response.speak("There was an 
error parsing the response from todoist");
				oldThis.emit(':responseReady');
			}
		});
		res.on("error",function() {
			var speech = "There was an error retrieving your 
data";
			oldThis.response.speak(speech);
			oldThis.response.cardRenderer(SKILL_NAME,speech);
			oldThis.emit(':responseReady');
		})
	});
	req.write("");
	req.end();
}
const handlers = {
	'LaunchRequest': function () {
		this.emit('GetListIntent');
	},
	'GetListIntent': function () {
		//this following line allows for testing from the lambda 
console. Sessions don't persist in dynamodb for lambda console testing
		//this.attributes['token'] = -1;
		//
		//see if this user has a token
		if (this.attributes && this.attributes['token'] && 
(this.attributes['token'] != -1)) {
			//yes they seem to have token, get list
			getList(this.attributes['token'],this);
		}  else {
			//no there is no token, start the OAUTH process
			startOAUTH(this);
		}
	},
	'AMAZON.HelpIntent': function () {
		const speechOutput = HELP_MESSAGE;
		const reprompt = HELP_REPROMPT;
		this.response.speak(speechOutput).listen(reprompt);
		this.emit(':responseReady');
	},
	'AMAZON.CancelIntent': function () {
		this.response.speak(STOP_MESSAGE);
		this.emit(':responseReady');
	},
	'AMAZON.StopIntent': function () {
		this.response.speak(STOP_MESSAGE);
		this.emit(':responseReady');
	},
};
exports.handler = function (event, context, callback) {
	const alexa = Alexa.handler(event, context, callback);
	alexa.APP_ID = APP_ID;
	alexa.dynamoDBTableName = 'final1';
	alexa.registerHandlers(handlers);
	alexa.execute();
};
