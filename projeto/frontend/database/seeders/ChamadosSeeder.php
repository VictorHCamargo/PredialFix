<?php

namespace Database\Seeders;

use App\Models\Local;
use App\Models\TipoProblema;
use App\Models\Equipamento;
use Illuminate\Database\Seeder;

class ChamadosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar Locais
        Local::firstOrCreate(
            ['sala_setor' => 'Sala A1', 'andar' => 1, 'bloco' => 'A'],
            []
        );
        Local::firstOrCreate(
            ['sala_setor' => 'Sala A2', 'andar' => 1, 'bloco' => 'A'],
            []
        );
        Local::firstOrCreate(
            ['sala_setor' => 'Lab Eletrônica', 'andar' => 2, 'bloco' => 'B'],
            []
        );
        Local::firstOrCreate(
            ['sala_setor' => 'Lab Hidráulica', 'andar' => 2, 'bloco' => 'B'],
            []
        );
        Local::firstOrCreate(
            ['sala_setor' => 'Sala C1', 'andar' => 1, 'bloco' => 'C'],
            []
        );

        // Criar Tipos de Problemas
        TipoProblema::firstOrCreate(
            ['categoria' => 'Elétrica'],
            ['prazo_estimadoimado' => 8]
        );
        TipoProblema::firstOrCreate(
            ['categoria' => 'Hidráulica'],
            ['prazo_estimadoimado' => 12]
        );
        TipoProblema::firstOrCreate(
            ['categoria' => 'Civil'],
            ['prazo_estimadoimado' => 24]
        );
        TipoProblema::firstOrCreate(
            ['categoria' => 'Mecânica'],
            ['prazo_estimadoimado' => 16]
        );
        TipoProblema::firstOrCreate(
            ['categoria' => 'HVAC'],
            ['prazo_estimadoimado' => 6]
        );

        // Criar Equipamentos
        Equipamento::firstOrCreate(
            ['tag_identificacao' => 'AC-001', 'status' => 'ativo'],
            ['nome_equipamento' => 'Ar Condicionado', 'marca' => 'LG']
        );
        Equipamento::firstOrCreate(
            ['tag_identificacao' => 'HID-001', 'status' => 'ativo'],
            ['nome_equipamento' => 'Bomba Hidráulica', 'marca' => 'Bosch']
        );
        Equipamento::firstOrCreate(
            ['tag_identificacao' => 'ELÉT-001', 'status' => 'ativo'],
            ['nome_equipamento' => 'Painel Elétrico', 'marca' => 'Siemens']
        );
        Equipamento::firstOrCreate(
            ['tag_identificacao' => 'MOT-001', 'status' => 'ativo'],
            ['nome_equipamento' => 'Motor Trifásico', 'marca' => 'WEG']
        );
        Equipamento::firstOrCreate(
            ['tag_identificacao' => 'COMP-001', 'status' => 'ativo'],
            ['nome_equipamento' => 'Compressor', 'marca' => 'Atlas Copco']
        );
    }
}
