<?php

namespace App\Http\Controllers;

use App\Models\DeviceType;
use Illuminate\Http\Request;

class DeviceTypeController extends Controller
{
    public function index()
    {
        return DeviceType::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'slug' => 'required|string|unique:device_types,slug',
        ]);

        return DeviceType::create($validated);
    }

    public function show(DeviceType $deviceType)
    {
        return $deviceType;
    }

    public function update(Request $request, DeviceType $deviceType)
    {
        $validated = $request->validate([
            'name' => 'string',
            'slug' => 'string|unique:device_types,slug,' . $deviceType->id,
        ]);
        $deviceType->update($validated);
        return $deviceType;
    }

    public function destroy(DeviceType $deviceType)
    {
        $deviceType->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
