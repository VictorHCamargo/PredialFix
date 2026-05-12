<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model {
    protected $table = 'feedbacks';
    protected $fillable = ['id_chamado', 'nota', 'comentario', 'data_feedback'];

    public function chamado() {
        return $this->belongsTo(Chamado::class, 'id_chamado');
    }
}
