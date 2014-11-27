<?php
class Common_functions {
	
	public function __construct() {

	}
	
	public static function periodo_actual() {
		$periodos=Sii::new_request('POST','/periodos');
		foreach ($periodos as $key => $value) {
			
		}
        return $res_referencias;
	}

}
?>