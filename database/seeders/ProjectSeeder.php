<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Project;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Project::create([
            'name' => 'Xyora',
            'code_project' => 'xyora',
            'logo' => 'project-logos/xyora.svg',
            'status' => 'active'
        ]);

        Project::create([
            'name' => 'Computask',
            'code_project' => 'computask',
            'logo' => 'project-logos/computask.png',
            'status' => 'active'
        ]);

        Project::create([
            'name' => 'MKT Central Directory',
            'code_project' => 'mcd',
            'logo' => 'project-logos/mkt-central-directory.png',
            'status' => 'active'
        ]);
    }
}
