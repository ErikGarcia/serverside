// WebSocketServer    
var WebSocketServer = require('ws').Server
	, http = require('http')
	, express = require('express')
	, app = express()
	, exec = require('child_process').exec
	,  moment = require('moment')
	, str2json = require('string-to-json')
	, validator = require('validator')
	, port = process.env.PORT || 8001;
app.use(express.static(__dirname + '/'));

// Create Server
var server = http.createServer(app);
server.listen(port);

console.log('http server listening on %d', port);

// Create WS
var wss = new WebSocketServer({server: server});
console.log('websocket server created');

wss.on('connection', function(ws) {
	
	console.log('websocket connection open');
	
	var lastResponse = '';
	
	// When WS gets a message
	ws.on('message',function(ipAddrees) {
		
			if(validator.isIP(ipAddrees))
			{
				// Log WS message
				console.log(moment().format() + ' - websocket message' +': ' + ipAddrees);
				
				// Sent a UDP request by PHP
				exec('php ./php/call-color-status.php ' + ipAddrees, function(error, stdout, stderr) {
					
					if(lastResponse != stdout)
					{
						error === null ? ws.send(stdout): ws.send('exec error: ' + error);
					}
					
					lastResponse = stdout;
				});
			}
			else
			{
				console.log(moment().format() + ' - wrong IP address' +': ' + ipAddrees);
			}
	});

	// On close WS event
	ws.on('close', function() {
		console.log('websocket connection close');
		//clearInterval(id);
	});
});