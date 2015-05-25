<?php
	 # UDP Response - Get a message from IP client
	$ip =  $_SERVER['argv'][1];
	$command = 'cmd=7272&nombre=ani&linea=0&';
	$fread  = '';
	$fp = stream_socket_client("udp://".$ip.":8544", $errno, $errstr);
	
	if(!$fp)
	{
		throw new UnexpectedValueException("Failed to connect:". $errno ."-". $errstr);
	}
	else
	{
		fwrite($fp, $command);
		stream_set_timeout($fp, 2);
		$fread = fread($fp, 65535);
		$info = stream_get_meta_data($fp);
		fclose($fp);
	}
	
	if ($info['timed_out'])
	{
		echo FALSE;
	}
	else
	{
		echo $fread;
	}       
?>