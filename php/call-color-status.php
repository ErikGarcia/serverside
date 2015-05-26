<?php
	include 'vendor/Ippad.php';
	
	$json = array();
	$scolor = '';
	$color = Ippad::statusIppad($_SERVER['argv'][1]);
	$listColor = array(
			'Disponible'  => 'green',
			'No Disponible' => 'blue',
			'Reposo' => 'yellow',
			'Baño' => 'yellow',
			'Coaching'=> 'yellow',
			'Break' => 'yellow',
			'Lunch' => 'yellow',
			'Conectando' => 'inactive',
			'Conectado' => 'inactive',
			'Ring' => 'inactive',
			'AfterCallWork' => 'inactive',
			'Retenido' => 'orange',
			'Desconectado' => 'gray'
	);
		
	foreach ($listColor as $key => $value){
		if($color == $key){
			$scolor = $value;
		}
	}
		
	$json[] = $color;
	$json[] = $scolor;
		
	echo json_encode($json, true);
?>