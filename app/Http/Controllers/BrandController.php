<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index()
    {
        return Brand::with('deviceType')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'device_type_id' => 'required|exists:device_types,id',
            'name' => 'required|string',
            'slug' => 'required|string|unique:brands,slug',
            'country' => 'nullable|string',
        ]);

        return Brand::create($validated);
    }

    public function show(Brand $brand)
    {
        return $brand->load('deviceType');
    }

    public function update(Request $request, Brand $brand)
    {
        $validated = $request->validate([
            'device_type_id' => 'exists:device_types,id',
            'name' => 'string',
            'slug' => 'string|unique:brands,slug,' . $brand->id,
            'country' => 'nullable|string',
        ]);

        $brand->update($validated);
        return $brand;
    }

    public function destroy(Brand $brand)
    {
        $brand->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
