<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TheLoai;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TheLoaiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return TheLoai::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'ten_the_loai' => 'required|string|max:255|unique:the_loai',
        ]);

        $theLoai = TheLoai::create($validatedData);

        return response()->json($theLoai, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(TheLoai $theLoai)
    {
        return $theLoai;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TheLoai $theLoai)
    {
        $validatedData = $request->validate([
            'ten_the_loai' => [
                'required',
                'string',
                'max:255',
                Rule::unique('the_loai')->ignore($theLoai->id),
            ],
        ]);

        $theLoai->update($validatedData);

        return response()->json($theLoai, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TheLoai $theLoai)
    {
        $theLoai->delete();

        return response()->json(null, 204);
    }
}
