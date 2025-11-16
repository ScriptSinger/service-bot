<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\ErrorCode;
use Illuminate\Http\Request;

class ErrorCodeController extends Controller
{

    public function index()
    {
        return ErrorCode::with('deviceModel')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'device_model_id' => 'required|exists:device_models,id',
            'code' => 'required|string',
            'description' => 'required|string',
            'severity' => 'required|in:info,warning,critical',
        ]);

        return ErrorCode::create($validated);
    }

    public function show(ErrorCode $errorCode)
    {
        return $errorCode->load('deviceModel');
    }

    public function update(Request $request, ErrorCode $errorCode)
    {
        $validated = $request->validate([
            'device_model_id' => 'exists:device_models,id',
            'code' => 'string',
            'description' => 'string',
            'severity' => 'in:info,warning,critical',
        ]);

        $errorCode->update($validated);

        return $errorCode;
    }

    public function destroy(ErrorCode $errorCode)
    {
        $errorCode->delete();

        return response()->json(['message' => 'Deleted']);
    }
}
