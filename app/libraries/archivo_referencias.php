<?php
class Archivo_referencias {
	
	public function __construct() {

	}
	
	public static function leer($file) {
		$fp = file($file);
        $res_referencias= null;
        try {
        	foreach ($fp as $line){
	            switch (strlen($line)){
	            	case 143:
		            	$explode_data=explode('.',substr($line,57));
		            	$data['convenio']=substr($line,0,6);
						$data['referencia']=substr($line,7,20);
						$data['fecha_de_pago']=substr($explode_data[3],11,10);
						$data['importe']=intval($explode_data[2]);
						$data['estado']=str_replace(" ","",(substr($explode_data[3],21,10)));
					//	$data['clave']=substr($explode_data[3],31,4);
					//	$data['tippo_pago']=substr($explode_data[3],35,3);
						$res_referencias['referencias'][]=$data;
	            	break;
	            	case 44:
	            		$res_referencias['infoFile'][str_replace(" ","",(substr($line,6,20)))]=intval(substr($line,27));	
	            	break;
	            }  
	        }
        } catch (Exception $e){
        	$res_referencias= null;
        }
        return $res_referencias;
	}

}
?>