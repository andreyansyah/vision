<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Project;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::orderBy('created_at', 'desc')->get();
        return view('project', compact('projects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $variations = [
            'project-logos/xyora.svg',
            'project-logos/computask.png',
            'project-logos/mkt-central-directory.png'
        ];
        $logo = $variations[array_rand($variations)];

        Project::create([
            'name' => $request->name,
            'code_project' => \Illuminate\Support\Str::slug($request->name),
            'logo' => $logo,
            'status' => 'active'
        ]);

        return redirect()->back()->with('success', 'Project created successfully!');
    }
}
