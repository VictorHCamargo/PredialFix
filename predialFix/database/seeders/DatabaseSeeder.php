<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $perfis = [
            ['nome' => 'funcionario', 'identificador' => 'FUN'],
            ['nome' => 'Gestor Geral', 'identificador' => 'GET'],
            ['nome' => 'adminstrador', 'identificador' => 'ADM'],
        ];

        foreach ($perfis as $perfil) {
            Profile::create($perfil);
        }
        // Cria um usuário de teste já amarrado ao novo sistema
        User::factory()->create([
            'nome' => 'Usuário Teste',     
            'email' => 'test@example.com',
            'cpf' => '12345678900',
            'id_profile' => 1,
        ]);
    }
}
