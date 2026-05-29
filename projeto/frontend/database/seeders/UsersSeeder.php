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
            'nome' => 'Gerente de Manutencao',
            'email' => 'gerente@predialfix.com',
            'senha' => Hash::make('gerente123'),
            'nivel_acesso' => 'gerente_manutencao',
            'cod_entrada' => 8888,
            'setor' => 'Manutencao',
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

        User::create([
            'nome' => 'Joao Aluno',
            'email' => 'joao@student.com',
            'senha' => Hash::make('aluno123'),
            'nivel_acesso' => 'aluno',
            'setor' => 'Classe 1',
            'ativo' => true,
        ]);

        User::create([
            'nome' => 'Maria Aluna',
            'email' => 'maria@student.com',
            'senha' => Hash::make('aluno123'),
            'nivel_acesso' => 'aluno',
            'setor' => 'Classe 1',
            'ativo' => true,
        ]);

        User::create([
            'nome' => 'Pedro Aluno',
            'email' => 'pedro@student.com',
            'senha' => Hash::make('aluno123'),
            'nivel_acesso' => 'aluno',
            'setor' => 'Classe 2',
            'ativo' => true,
        ]);
    }
}
