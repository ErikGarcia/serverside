<?php
	include 'Ippad.php';
		
	$newCall = Ippad::newCall($_SERVER['argv'][1]);
	$idCall = Ippad::idCall($_SERVER['argv'][1]);
	if($newCall != FALSE && $idCall != FALSE){
		$json['newCall'] = $newCall;
		$json['idCall'] = $idCall;
	}else{
		$json['newCall'] = 'fail';
		$json['idCall'] = 'fail';
	}

	echo json_encode($json, true);
?>