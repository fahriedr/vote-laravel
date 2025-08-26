<?php

namespace Database\Seeders;

use App\Helpers\Helper;
use App\Models\Option;
use App\Models\Poll;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@email.com',
            'password' => Hash::make('admin123'),
        ]);

        // Buat 3 user random
        $users = User::factory()->count(3)->create();

        // Gabungkan admin + user random
        $allUsers = $users->push($admin);

        // Setiap user punya 3 polling
        foreach ($allUsers as $user) {
            for ($i = 1; $i <= 3; $i++) {
                $poll = Poll::create([
                    'user_id' => $user->id,
                    'unique_id' => Helper::generateUniqueId('POLL', 10),
                    'status' => 1,
                    'question' => fake()->text(50)
                ]);

                // Setiap polling punya 3 option
                $options = [];
                for ($j = 1; $j <= 3; $j++) {
                    $options[] = Option::create([
                        'poll_id' => $poll->id,
                        'option_text' => fake()->word,
                    ]);
                }

                // Setiap polling punya 10–20 votes random
                $voteCount = rand(10, 20);
                for ($k = 0; $k < $voteCount; $k++) {
                    $randomOption = collect($options)->random();

                    Vote::create([
                        'browser_fingerprint' => Str::random(12),
                        'poll_id' => $poll->id,
                        'option_id' => $randomOption->id,
                    ]);
                }
            }
        }
    }
}
