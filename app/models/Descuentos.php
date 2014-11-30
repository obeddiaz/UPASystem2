<?php

class Descuentos extends \Eloquent {

    protected $fillable = ['id', 'tipo_importe_id', 'adeudos_id', 'importe', 'created_at', 'updated_at'];
    protected $table = 'descuentos';
    public $timestamps = true;

    public static function obtenerAdeudosDeDescuentos($id = null) {

        $query = Descuentos::join('adeudos', 'adeudos.id', '=', 'descuentos.adeudos_id')
                ->join('sub_conceptos', 'adeudos.sub_concepto_id', '=', 'sub_conceptos.id');

        if (isset($id)) {
            $query = $query->where('descuentos.id', $id);
            $res = $query->first();
        } else {
            $res = $query->get();
        }


        return $res;
    }

    public static function obtenerDescuentoPorAdeudo($id = null) {

        $query = Descuentos::join('adeudos', 'adeudos.id', '=', 'descuentos.adeudos_id')
                ->join('sub_conceptos', 'adeudos.sub_concepto_id', '=', 'sub_conceptos.id')
                ->select('descuentos.*');

        if (isset($id)) {
            $query = $query->where('adeudos.id', $id);
            $res = $query->get();
        } else {
            $res = $query->get();
        }


        return $res;
    }

}
