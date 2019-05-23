/* eslint-disable func-names */ /* eslint quote-props: ["error", 
"consistent"]*/ /**
 * This sample demonstrates a simple skill built with the Amazon Alexa 
Skills
 * nodejs skill development kit.
 * This sample supports multiple lauguages. (en-US, en-GB, de-DE).
 * The Intent Schema, Custom Slots and Sample Utterances for this skill, 
as well
 * as testing instructions are located at 
https://github.com/alexa/skill-sample-nodejs-fact
 **/ 'use strict'; const Alexa = require('alexa-sdk'); var http = 
require("http"); 
//========================================================================================================================================= 
//TODO: The items below this comment need your attention. 
//========================================================================================================================================= 
//Replace with your app ID (OPTIONAL).  You can find this value at the 
top of your skill's page on http://developer.amazon.com. //Make sure to 
enclose your value in quotes, like this: const APP_ID = 
'amzn1.ask.skill.bb4045e6-b3e8-4133-b650-72923c5980f1'; const APP_ID = 
undefined; const SKILL_NAME = 'Info Data'; const GET_FACT_MESSAGE = 
"Here's your fact: "; const HELP_MESSAGE = 'You can say tell me info, 
or, you can say exit... What can I help you with?'; const HELP_REPROMPT 
= 'What can I help you with?'; const STOP_MESSAGE = 'Goodbye!'; 
//========================================================================================================================================= 
//Editing anything below this line might break your skill. 
//========================================================================================================================================= 
const handlers = {
    'LaunchRequest': function () {
        this.emit('GetPageIntent');
    },
    'AddCommentIntent' : function () {
    	var input = this.event.request.intent.slots.commentSlot.value;
    	//const speechOutput = input;
        //this.response.speak(speechOutput);
        //this.emit(':responseReady'); try { var options = {
  "method": "PUT",
  "hostname":"ceclnx01.cec.miamioh.edu",
  "path": 
"~bowserbl/cse451-bowserbl-web/final2/rest.php/api/v1/Comment",
  "headers": {
    "Content-Type": "application/json"
  }
};
var req = http.request(options, function (res) {
  var chunks = [];
  res.on("data", function (chunk) {
    chunks.push(chunk);
  });
  res.on("end", function () {
    var body = Buffer.concat(chunks);
    console.log(body.toString());
  });
});
req.write(JSON.stringify({ password: 'class', comment: 'WORKS' })); 
req.end(); this.response.speak("Here"); this.emit(':responseReady');
    } catch (e) {
        this.response.speak(e);
        this.emit(':responseReady');
    }
    
    },
    'GetCommentIntent': function () {
        var oldThis = this;	//store this so we can reference the 
alexa object from inside the callback handlers
        try {
            
			//make call to campbest rest server
			var req = 
http.get('http://ceclnx01.cec.miamioh.edu/~bowserbl/cse451-bowserbl-web/final2/rest.php/api/v1/Comments/Last' 
,function(res) {
				var chunks = [];
				//handle chunks of data, store
				res.on('data',function(chunk) {
					chunks.push(chunk);
				});
				//called at end, process data
				res.on('end',function() {
					var body = 
Buffer.concat(chunks);
					console.log("body",body.toString());
					//set default to respond with 
error
					var speechOutput = "error";
					try {
						var json = 
JSON.parse(body.toString());
						//try to extract message 
from json
						speechOutput = 
json.Comment[0].comment;
						if (speechOutput == 
null) {
							speechOutput = 
"Could not load comment";
						}
					} catch (e) {
						console.log("Error on 
parse",e,body.toString());
						speechOutput ="Error on 
comment parse";
					}
					//speak the response
					oldThis.response.speak(speechOutput); 
// speechOutput
					oldThis.emit(':responseReady');
				});
				res.on('error',function() {
					oldThis.response.speak("Error 
getting comment");
					oldThis.emit(':responseReady');
				});
			});
		} catch (e) {
			//global error handler
			console.log("Error",e);
			oldThis.response.speak("Something went wrong 
with this skill, try again.");
			oldThis.emit(':responseReady');
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
    alexa.registerHandlers(handlers);
    alexa.execute();
};
