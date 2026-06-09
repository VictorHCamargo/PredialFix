<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder {
    public function run(): void {
        User::create([
            'nome' => 'Administrador',
            'email' => 'admin@predialfix.com',
            'senha' => Hash::make('admin123'),
            'nivel_acesso' => 'administrador',
            'cod_entrada' => 9999,
            'setor' => 'TI',
            'ativo' => true,
        ]);

        User::create([
            'nome' => 'Tecnico de Manutencao',
            'email' => 'tecnico@predialfix.com',
            'senha' => Hash::make('tecnico123'),
            'nivel_acesso' => 'tecnico_manutencao',
            'cod_entrada' => 7777,
            'setor' => 'Manutencao',
            'ativo' => true,
        ]);

        User::create([
            'nome' => 'Professor Joao Silva',
            'email' => 'professor@predialfix.com',
            'senha' => Hash::make('prof123'),
            'nivel_acesso' => 'professor',
            'setor' => 'Docencia',
            'ativo' => true,
        ]);

    }
}
