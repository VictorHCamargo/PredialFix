<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Seed the application's database with test users.
     */
    public function run(): void
    {
        // Usuário Administrador
        User::create([
            'nome' => 'Administrador',
            'email' => 'admin@predialfix.com',
            'senha' => Hash::make('admin123'),
            'nivel_acesso' => 'administrador',
            'cod_entrada' => 9999,
            'setor' => 'TI',
            'ativo' => true,
        ]);

        // Usuário Gerente de Manutenção
        User::create([
            'nome' => 'Gerente de Manutenção',
            'email' => 'gerente@predialfix.com',
            'senha' => Hash::make('gerente123'),
            'nivel_acesso' => 'gerente_manutencao',
            'cod_entrada' => 8888,
            'setor' => 'Manutenção',
            'ativo' => true,
        ]);

        // Usuário Técnico de Manutenção
        User::create([
            'nome' => 'Técnico de Manutenção',
            'email' => 'tecnico@predialfix.com',
            'senha' => Hash::make('tecnico123'),
            'nivel_acesso' => 'tecnico_manutencao',
            'cod_entrada' => 7777,
            'setor' => 'Manutenção',
            'ativo' => true,
        ]);

        // Usuário Professor
        User::create([
            'nome' => 'Professor João Silva',
            'email' => 'professor@predialfix.com',
            'senha' => Hash::make('prof123'),
            'nivel_acesso' => 'professor',
            'setor' => 'Docência',
            'ativo' => true,
        ]);

        // Usuários Alunos de Teste
        User::create([
            'nome' => 'João Aluno',
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

        // Usuário Visitante (padrão)
        User::create([
            'nome' => 'Visitante Padrão',
            'email' => 'visitor@predialfix.com',
            'senha' => Hash::make('visitor123'),
            'nivel_acesso' => 'visitante',
            'ativo' => true,
        ]);
    }
}
