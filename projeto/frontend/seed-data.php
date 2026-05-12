<?php

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/bootstrap/app.php';

use App\Models\Local;
use App\Models\TipoProblema;
use App\Models\Equipamento;

// Criar Locais
Local::firstOrCreate(
    ['nome' => 'Bloco A, Sala 1'],
    ['descricao' => 'Sala de aula A1']
);
Local::firstOrCreate(
    ['nome' => 'Bloco A, Sala 2'],
    ['descricao' => 'Sala de aula A2']
);
Local::firstOrCreate(
    ['nome' => 'Bloco B, Sala 1'],
    ['descricao' => 'Laboratório de Eletrônica']
);
Local::firstOrCreate(
    ['nome' => 'Bloco B, Sala 2'],
    ['descricao' => 'Laboratório de Hidráulica']
);
Local::firstOrCreate(
    ['nome' => 'Bloco C, Sala 1'],
    ['descricao' => 'Sala de Aula C1']
);

// Criar Tipos de Problemas
TipoProblema::firstOrCreate(
    ['nome' => 'Elétrica'],
    ['descricao' => 'Problemas relacionados à instalação elétrica']
);
TipoProblema::firstOrCreate(
    ['nome' => 'Hidráulica'],
    ['descricao' => 'Problemas relacionados aos sistemas hidráulicos']
);
TipoProblema::firstOrCreate(
    ['nome' => 'Civil'],
    ['descricao' => 'Problemas relacionados à estrutura civil']
);
TipoProblema::firstOrCreate(
    ['nome' => 'Mecânica'],
    ['descricao' => 'Problemas relacionados a equipamentos mecânicos']
);
TipoProblema::firstOrCreate(
    ['nome' => 'HVAC'],
    ['descricao' => 'Problemas de ar condicionado e ventilação']
);

// Criar Equipamentos
Equipamento::firstOrCreate(
    ['nome' => 'Ar Condicionado', 'status' => 'ativo'],
    ['descricao' => 'Sistema de climatização']
);
Equipamento::firstOrCreate(
    ['nome' => 'Bomba Hidráulica', 'status' => 'ativo'],
    ['descricao' => 'Equipamento hidráulico']
);
Equipamento::firstOrCreate(
    ['nome' => 'Painel Elétrico', 'status' => 'ativo'],
    ['descricao' => 'Painel de distribuição elétrica']
);
Equipamento::firstOrCreate(
    ['nome' => 'Motor Trifásico', 'status' => 'ativo'],
    ['descricao' => 'Motor elétrico trifásico']
);
Equipamento::firstOrCreate(
    ['nome' => 'Compressor', 'status' => 'ativo'],
    ['descricao' => 'Compressor de ar']
);

echo "Dados carregados com sucesso!";
