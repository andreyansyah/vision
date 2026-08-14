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
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->call(ProjectSeeder::class);

        // Seed default notes
        \App\Models\Note::create([
            'title' => 'Meeting Outline',
            'content' => 'Discuss wireframes with the creative design team. Review progress on typography, colors, and the new visual hierarchy...'
        ]);

        \App\Models\Note::create([
            'title' => 'App Features Idea',
            'content' => 'Implement swipe animations on tasks, expandable monthly calendar layout, brand color pulse indicators, and inset dividers...'
        ]);

        \App\Models\Note::create([
            'title' => 'Weekly Groceries List',
            'content' => 'Apples, Organic bananas, Almond milk, Dark roast coffee beans, Whole wheat bread, Spinach, Avocados...'
        ]);
    }
}
