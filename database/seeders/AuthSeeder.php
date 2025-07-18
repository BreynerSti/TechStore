<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear usuario administrador si no existe
        if (!User::where('email', 'admin@techstore.com')->exists()) {
            User::create([
                'name' => 'Administrador',
                'email' => 'admin@techstore.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '123456789',
                'address' => 'Dirección del Administrador',
                'city' => 'Ciudad',
            ]);
            $this->command->info('Usuario administrador creado.');
        } else {
            $this->command->info('Usuario administrador ya existe.');
        }

        // Crear un solo usuario cliente de ejemplo si no existe
        if (!User::where('email', 'cliente@ejemplo.com')->exists()) {
            User::create([
                'name' => 'Cliente Ejemplo',
                'email' => 'cliente@ejemplo.com',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'phone' => '987654321',
                'address' => 'Calle Principal 123',
                'city' => 'Ciudad',
            ]);
            $this->command->info('Usuario cliente creado.');
        } else {
            $this->command->info('Usuario cliente ya existe.');
        }

        $this->command->info('¡Proceso de seeding completado!');
        $this->command->info('Credenciales disponibles:');
        $this->command->info('Admin: admin@techstore.com / password');
        $this->command->info('Cliente: cliente@ejemplo.com / password');
    }
} 