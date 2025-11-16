<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Manual;
use Illuminate\Http\Request;

class ManualController extends Controller
{
    public function index()
    {
        return Manual::with('deviceModel')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'device_model_id' => 'required|exists:device_models,id',
            'title' => 'required|string',
            'file_url' => 'required|string',
            'language' => 'nullable|string',
        ]);

        return Manual::create($validated);
    }

    public function show(Manual $manual)
    {
        return $manual->load('deviceModel');
    }

    public function update(Request $request, Manual $manual)
    {
        $validated = $request->validate([
            'device_model_id' => 'exists:device_models,id',
            'title' => 'string',
            'file_url' => 'string',
            'language' => 'nullable|string',
        ]);

        $manual->update($validated);

        return $manual;
    }

    public function destroy(Manual $manual)
    {
        $manual->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
