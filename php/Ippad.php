<?php
class Ippad{
    
    public static $list = array(
        'Desconectado' => 'Desconectado', 
        'Disponible'  => 'Disponible',
        'No+Disponible' => 'No Disponible',
        'Auxiliar+0' => 'Reposo',
        'Auxiliar+1' => 'Baño',
        'Auxiliar+2'=> 'Coaching',
        'Auxiliar+3' => 'Break',
        'Auxiliar+4' => 'Lunch',
        'Conectando' => 'Conectando',
        'Conectado' => 'Conectado',
        'Ring' => 'Ring',
        'AfterCallWork' => 'AfterCallWork',
        'Retenido' => 'Retenido'
    );
    
	public static function loginIppad($username, $password, $ip){
		$command ="cmd=7200&loginid=".$username."&password=".$password."&";
		return self::socket($command, $ip);
	}
	
	public static function statusIppad($ip){
		$command = "cmd=7290&";
		$string =  self::socket($command, $ip);
		$pieces = explode("&", $string);
		array_pop($pieces); 
		return self::getStatusClick($pieces);
	}
	
	public static function setStatusClick($command, $ip){
		return self::socket($command, $ip);
	}

	private static function getStatusClick(array $status){
		$menu="";
		if ($status != null) {
			$status = str_replace("estado=", "", $status[2]);
			foreach (self::$list as $key => $value):					
		   		if($status == $key):
			  		$menu.=$value;
		   		endif;
			endforeach;
		}
 		return $menu;
    }
		
	public static function executePhone($phone, $ip){					
		$command = "cmd=7400&dnis=".$phone."&";
		return self::socket($command, $ip);
	}
	
	public static function ringOffPhone($ip, $linea){
	    $command = "cmd=7500&linea=".$linea."&";
		return self::socket($command, $ip);
	}
	
	public static function holdR($ip, $linea){	
		return self::socket("cmd=7430&linea=".$linea."&", $ip);
	}

    public static function holdRc($ip, $linea){
        return self::socket("cmd=7432&linea=".$linea."&", $ip);
    }
	
	public static function newCall($ip){
		$command = "cmd=7272&nombre=ani&linea=0&";
		return self::socket($command, $ip);
	}
	
    public static function call($ip){
		$command = "cmd=7410&";
		return self::socket($command, $ip);
	}
	
	public  static function newDial($ip){
		$command = "cmd=7272&nombre=dnis&linea=0&";
		return self::socket($command, $ip);		
	}
	
	public static function transfer($number, $ip){
		$command = "cmd=7442&linea=0&destino=".$number."&";
		return self::socket($command, $ip);
	}
	
	public static function idCall($ip){
		$command = "cmd=7272&nombre=idLlamada&linea=0&";
		return self::socket($command, $ip);
	}
	
	public static function logout($ip){
		$command ="cmd=7210&";
		self::socket($command, $ip);
	}
	
	private static function socket($command, $ip){
		$fread  = '';
		$fp = stream_socket_client("udp://".$ip.":8544", $errno, $errstr);
		if(!$fp){
			throw new UnexpectedValueException("Failed to connect:". $errno ."-". $errstr);
		}else{
			fwrite($fp, $command);
            stream_set_timeout($fp, 2);
			$fread = fread($fp, 65535);
			$info = stream_get_meta_data($fp);
			fclose($fp);
		}
        
        if ($info['timed_out']) {
            return FALSE;
        } else {
            return $fread;
        }
        
	}
}