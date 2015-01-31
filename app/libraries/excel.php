<?php

class Excel_Constructor {

	public function __construct() {
     
    }

    public function export($adeudos) {
		Excel::create('Reporte '.date('Y-m-d'), function($excel) use($adeudos) {
			$excel->sheet('Adeudos', function($sheet) use($adeudos) {
		        $sheet->loadView('excel/create_excel', array('adeudos' => $adeudos));
		    });
		})->download('xls');
	}
}