<?php
class Archivo_referencias {
	
	public function __construct() {

	}
	
	public function leer($file) {
		$fileOpen = fopen($file['archivo']['tmp_name'], "r") or exit("No se ha podido leer el archivo!");
		//Output a line of the file until the end is reached
		while(!feof($fileOpen))
		{
			echo fgets($fileOpen). "<br/>";
		}
		fclose($fileOpen);
	}

}
?>