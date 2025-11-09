<?php

namespace App\Http\Controllers;

use App\Models\DeviceModel;
use Illuminate\Http\Request;

class DeviceModelController extends Controller
{
    public function index()
    {
        return DeviceModel::with('brand')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'brand_id' => 'required|exists:brands,id',
            'name' => 'required|string',
            'description' => 'nullable|string',
            'year_from' => 'nullable|integer',
            'year_to' => 'nullable|integer',
            'image_url' => 'nullable|string',
            'active' => 'boolean',
        ]);

        return DeviceModel::create($validated);
    }

    public function show(DeviceModel $deviceModel)
    {
        return $deviceModel->load(['brand', 'manuals', 'testModes', 'errorCodes']);
    }

    public function update(Request $request, DeviceModel $deviceModel)
    {
        $validated = $request->validate([
            'brand_id' => 'exists:brands,id',
            'name' => 'string',
            'description' => 'nullable|string',
            'year_from' => 'nullable|integer',
            'year_to' => 'nullable|integer',
            'image_url' => 'nullable|string',
            'active' => 'boolean',
        ]);

        $deviceModel->update($validated);

        return $deviceModel;
    }

    public function destroy(DeviceModel $deviceModel)
    {
        $deviceModel->delete();

        return response()->json(['message' => 'Deleted']);
    }
}
