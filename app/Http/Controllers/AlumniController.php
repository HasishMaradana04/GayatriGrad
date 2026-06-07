<?php

namespace App\Http\Controllers;

use App\Models\Alumni;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class AlumniController extends Controller
{
    public function index(Request $request): View
    {
        $query = Alumni::query();

        if ($request->filled('q')) {
            $keyword = (string) $request->string('q');
            $query->where(function ($builder) use ($keyword): void {
                $builder->where('name', 'like', "%{$keyword}%")
                    ->orWhere('department', 'like', "%{$keyword}%")
                    ->orWhere('current_position', 'like', "%{$keyword}%")
                    ->orWhere('organization', 'like', "%{$keyword}%")
                    ->orWhere('location', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('batch')) {
            $query->where('graduation_year', $request->integer('batch'));
        }

        if ($request->filled('department')) {
            $query->where('department', (string) $request->string('department'));
        }

        if ($request->filled('location')) {
            $query->where('location', (string) $request->string('location'));
        }

        return view('alumni.index', [
            'alumni' => $query->orderByDesc('graduation_year')->paginate(12)->withQueryString(),
            'distinguished' => Cache::remember('alumni.distinguished', now()->addHour(), fn () => Alumni::query()->distinguished()->latest()->take(6)->get()),
            'filters' => Cache::remember('alumni.filters', now()->addHour(), fn () => [
                'batches' => Alumni::query()->distinct()->orderByDesc('graduation_year')->pluck('graduation_year'),
                'departments' => Alumni::query()->whereNotNull('department')->distinct()->orderBy('department')->pluck('department'),
                'locations' => Alumni::query()->whereNotNull('location')->distinct()->orderBy('location')->pluck('location'),
            ]),
        ]);
    }
}
