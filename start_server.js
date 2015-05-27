var net = require('net')
	, moment = require('moment')
	, validator = require('validator')
	, exec = require('child_process').exec;

// EXAMPLE OF PROGRAMMED TASK
//node C:\Users\DESARROLLO\git\serverside\start_server.js 8000 "node C:\Users\DESARROLLO\git\serverside\server-call-status.js"

//the machine to scan
var host = '127.0.0.1';
var port = process.argv[2];
var timeout = 2000;

command =  process.argv[3];

var rePattern = new RegExp(/^(\s*)?start/);
var arrMatches = command.toLowerCase().match(rePattern);

var PortValid = validator.isNumeric(port);
var CommandValid = arrMatches === null ? true: false;

if(PortValid && CommandValid)
{
	(function(port) {
	    // console.log('CHECK: ' + port);
	    var s = new net.Socket();
	    
	    s.setTimeout(timeout, function() { s.destroy(); });
	    
	    // Port is open
	    s.connect(port, host, function() {
	        console.log(moment().format() + ' OPENED: ' + port);
	        s.destroy();
	    });
	    
	    // Port is close
	    s.on('error', function(e) {
	    	console.log(moment().format() + ' CLOSED: ' + port);
	        s.destroy();
	        
	        // TODO - send alert
	        
	        // Start server
	        exec('START ' + command, function(error, stdout, stderr) {
						
				if(error === null)
				{
					console.log(moment().format() + ' EXECUTED: ' + 'start ' + command);
				}
				else
				{
					console.log(moment().format() + ' Error: ' + stderr);
				}
			});
	    });
	})(port);
}
else if(!PortValid)
{
	 console.log(moment().format() + ' INVALID PORT: ' + port);
}
else if(!CommandValid)
{
	 console.log(moment().format() + ' COMMAND START IS NOT VALID: ' + port);
}