<?php

class PruebaAPIController extends Controller {

    public function index() {
        $sii = new Sii();
        return $sii->new_request('GET','/check/auth');
    }

}
