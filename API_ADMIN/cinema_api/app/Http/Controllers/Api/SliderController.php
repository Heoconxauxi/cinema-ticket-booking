<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sliders = Slider::where('TrangThai', 1) 
                         ->orderBy('SapXep', 'asc')
                         ->with('phim') 
                         ->get();

        return response()->json($sliders);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'TenSlider' => 'required|string|max:255',
            'URL' => 'nullable|string|max:255',
            'MaPhim' => 'nullable|exists:phim,MaPhim', 
            'Anh' => 'required|string|url',
            'SapXep' => 'nullable|integer',
            'TrangThai' => 'nullable|boolean',
            'NguoiTao' => 'nullable|string', 
        ]);

        $slider = Slider::create($validatedData);

        return response()->json($slider->load('phim'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $slider = Slider::with('phim')->findOrFail($id);
        return response()->json($slider);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $slider = Slider::findOrFail($id);

        $validatedData = $request->validate([
            'TenSlider' => 'sometimes|string|max:255',
            'URL' => 'nullable|string|max:255',
            'MaPhim' => 'nullable|exists:phim,MaPhim',
            'Anh' => 'sometimes|string|url',
            'SapXep' => 'nullable|integer',
            'TrangThai' => 'nullable|boolean',
            'NguoiCapNhat' => 'nullable|string',
        ]);
        
        $slider->update($validatedData);

        return response()->json($slider->load('phim'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $slider = Slider::findOrFail($id);
        
        $slider->delete();

        return response()->json(null, 204);
    }
}
