<?php

class Excel_Constructor {

    public function __construct() {
        
    }

    public function export($adeudos) {
        //var_dump($adeudos);die();
        Excel::create('Reporte ' . date('Y-m-d'), function($excel) use($adeudos) {
            $excel->sheet('Adeudos', function($sheet) use($adeudos) {
                $sheet->loadView('excel/create_excel', array('adeudos' => $adeudos));
            });
        })->export('xlsx');
//        Excel::create('Laravel Excel', function($excel) {
//
//            $excel->sheet('Adeudos', function($sheet) {
//
//                $sheet->setOrientation('landscape');
//            });
//        })->download('xlsx');
    }

}
