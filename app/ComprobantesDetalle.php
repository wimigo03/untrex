<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Comprobantes;
use App\PlanCuentas;
use DB;

class ComprobantesDetalle extends Model
{
    protected $table = 'comprobantes_detalles';
    protected $fillable = [
        'id',
        'comprobante_id',
        'plancuenta_id',
        'plancuentaauxiliar_id',
        'centro_id',
        'glosa',
        'debe',
        'haber',
        'tipo_transaccion',
        'cheque_nro',
        'cheque_orden'
    ];
    use SoftDeletes;
    protected $dates =['deleted_at'];

    public function comprobante(){
        return $this->belongsTo(Comprobantes::class,'comprobante_id','id');
    }

    public function cuenta(){
        return $this->belongsTo(PlanCuentas::class,'plancuenta_id','id');
    }

    public function scopeByProyecto($query, $proyecto){
        if ($proyecto != null) {
                return $query
                    ->whereIn('comprobante_id', function ($subquery) use($proyecto) {
                        $subquery->select('id')
                            ->from('comprobantes')
                            ->where('proyecto_id',$proyecto)
                            ->whereNull('deleted_at');
                    });
        }
    }

    public function scopeByFechas($query, $fecha_i,$fecha_f){
        if ($fecha_i && $fecha_f) {
                $fecha_i = date('Y-m-d 23:59:59', strtotime(str_replace('/', '-', $fecha_i)));
                $fecha_f = date('Y-m-d 23:59:59', strtotime(str_replace('/', '-', $fecha_f)));
                return $query
                    ->whereIn('comprobante_id', function ($subquery) use($fecha_i,$fecha_f) {
                        $subquery->select('id')
                            ->from('comprobantes')
                            ->where('fecha','>=',$fecha_i . '%')
                            ->where('fecha','<=',$fecha_f . '%')
                            ->whereNull('deleted_at');
                    });
        }
    }

    public function scopeByTipoComprobante($query, $tipo_comp){
        if ($tipo_comp != null) {
            if ($tipo_comp != 4) {
                return $query
                    ->whereIn('comprobante_id', function ($subquery) use($tipo_comp) {
                        $subquery->select('id')
                            ->from('comprobantes')
                            ->where('tipo',$tipo_comp)
                            ->whereNull('deleted_at');
                    });
            }else{
                return $query
                    ->whereIn('comprobante_id', function ($subquery) use($tipo_comp) {
                        $subquery->select('id')
                            ->from('comprobantes')
                            ->whereIn('tipo',[1,2,3])
                            ->whereNull('deleted_at');
                    });
            }
        }
    }

    public function scopeByEstado($query, $estado){
        if ($estado != null) {
            if ($estado != 3) {
                return $query
                    ->whereIn('comprobante_id', function ($subquery) use($estado) {
                        $subquery->select('id')
                            ->from('comprobantes')
                            ->where('status',$estado)
                            ->whereNull('deleted_at');
                    });
            }else{
                return $query
                    ->whereIn('comprobante_id', function ($subquery) use($estado) {
                        $subquery->select('id')
                            ->from('comprobantes')
                            ->whereIn('status',[0,1,2])
                            ->whereNull('deleted_at');
                    });
            }
        }
    }
}