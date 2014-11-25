<?php

class PruebaAPIController extends Controller {
    

    public function index() {
        $sii = new Sii();
//        $my_user=$sii->login('fernando.medrano@upa.edu.mx','upa.loopa');
//        var_dump($my_user); 
        //$data=  Session::get('user');
        //var_dump($sii);
        //return $sii->getAlumnosActivos();
        return $sii->new_request('POST','/periodos');
    }

}
