<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstoqueInterno extends Model
{
    protected $table = 'estoque_interno';
    protected $primaryKey = 'id_estoque';
    
    protected $fillable = [
        'nome_item',
        'descricao',
        'quantidade',
        'categoria',
        'localizacao',
        'valor_unitario',
        'valor_total',
        'codigo_patrimonio',
        'status_item',
        'data_entrada',
        'data_saida',
        'observacoes',
        'id_usuario_cadastro'
    ];

    protected $casts = [
        'data_entrada' => 'datetime',
        'data_saida' => 'datetime'
    ];

    public function usuarioCadastro()
    {
        return $this->belongsTo(User::class, 'id_usuario_cadastro');
    }
}
