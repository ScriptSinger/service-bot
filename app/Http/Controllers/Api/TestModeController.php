<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\TestMode;
use Illuminate\Http\Request;

class TestModeController extends Controller
{

    public function index()
    {
        return TestMode::with('deviceModel')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'device_model_id' => 'required|exists:device_models,id',
            'entry_combination' => 'required|string',
            'exit_combination' => 'nullable|string',
            'notes' => 'nullable|string',
            'image_url' => 'nullable|string',
        ]);
        return TestMode::create($validated);
    }

    public function show(TestMode $testMode)
    {
        return $testMode->load('deviceModel');
    }

    public function update(Request $request, TestMode $testMode)
    {
        $validated = $request->validate([
            'device_model_id' => 'exists:device_models,id',
            'entry_combination' => 'string',
            'exit_combination' => 'nullable|string',
            'notes' => 'nullable|string',
            'image_url' => 'nullable|string',
        ]);
        $testMode->update($validated);
        return $testMode;
    }

    public function destroy(TestMode $testMode)
    {
        $testMode->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
