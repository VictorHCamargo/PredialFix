<?php

namespace Database\Seeders;

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
        // Comentado para evitar duplicação de dados
        // User::factory(10)->create();

        // Chamar seeders de usuários e chamados
        $this->call([
            UsersSeeder::class,
            ChamadosSeeder::class,
        ]);
    }
}
