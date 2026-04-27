<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Developer;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DeveloperWebController extends Controller
{
    /**
     * Página principal: lista todos os developers
     * Find por termo via query string ?terms=
     */
    public function index(Request $request): View
    {
        $terms = $request->query('terms');

        if ($terms) {
            // termo
            $developers = Developer::search($terms)->limit(20)->get();
        } else {
            // Lista os primeiros 20
            $developers = Developer::latest()->limit(20)->get();
        }

        $total = Developer::count();

        return view('developers.index', compact('developers', 'total', 'terms'));
    }

    /**
     * Página de detalhe de developer pelo UUID
     */
    public function show(string $id): View
    {
        $developer = Developer::findOrFail($id);

        return view('developers.show', compact('developer'));
    }

    /**
     * Página de criar novo developer
     */
    public function create(): View
    {
        return view('developers.create');
    }
}
