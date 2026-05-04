<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoProblema extends Model {
    protected $table = 'tipo_problemas';
    protected $primaryKey = 'id_tipo';
    protected $fillable = ['categoria', 'prazo_estimado'];

    public function chamados() {
        return $this->hasMany(Chamado::class, 'id_tipo');
    }
}
