<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class ComprobantesFiscales extends Model
{
    protected $table = 'comprobantes_fiscales';
    protected $fillable = [
        'comprobante_interno_id',
        'user_id',
        'socio_id',
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