<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class Comprobantes extends Model
{
    protected $table = 'comprobantes';
    protected $fillable = [
        'user_id',
        'proyecto_id',
        'user_autorizado_id',
        'nro_comprobante',
        'nro_comprobante_id',
        'tipo_cambio',
        'ufv',
        'tipo',
        'entregado_recibido',
        'fecha',
        'concepto',
        'monto',
        'moneda',
        'copia',
        'status'
    ];
    use SoftDeletes;
    protected $dates =['deleted_at'];

    public function getEstadoCompAttribute(){
        switch ($this->status) {
            case '0':
                return "BORRADOR";
            case '1':
                return "APROBADO";
            case '2':
                return "ANULADO";
        }
    }
}