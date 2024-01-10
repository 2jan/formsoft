<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Invoice;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Sequence;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()
            ->has(
                Invoice::factory()
                    ->count(36)
                    ->sequence(fn (Sequence $sequence) => ['invoice_number' => 'F/' . ($sequence->index + 1)])
            )
            ->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);
    }
}
