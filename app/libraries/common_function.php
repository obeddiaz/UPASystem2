<?php
class Common_functions {
	
	public function __construct() {

	}
	
	public static function periodo_actual() {
		$periodos=Sii::new_request('POST','/periodos');
		$res=array();
		foreach ($periodos as $key => $value) {
			if ($value['actual']=='1') {
				$res=$value;
			}
		}
        return $res;
	}
	

}
?>