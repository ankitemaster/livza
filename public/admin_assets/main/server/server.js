const WebSocket = require('wss');

const cron = require("node-cron");
const fs = require("fs");

// uncomment these line for ssl configuration
var privateKey = fs.readFileSync('', 'utf8');
var certificate = fs.readFileSync('', 'utf8');
var credentials = { key: privateKey, cert: certificate };
var https = require('https');
var httpsServer = https.createServer(credentials);
// ssl configuration end

// uncomment these lines for without ssl configuration
      //var https = require('http');
      //var httpsServer = https.createServer();
// without ssl configuration end

const webPort = 3008;
httpsServer.listen(webPort, function(){
	
});
const wss=new WebSocket.Server({server:httpsServer});


wss.on('connection',ws=>{
	ws.room=[];
	console.log('connected');

	cron.schedule("0,10,20,30,40,50 * * * * *", function() {
		console.log("running a task every 10 secs");
		ws.send();
	});

	ws.on('error',e=>console.log(e))
	ws.on('close',(e)=>console.log('websocket closed'+e))

})





