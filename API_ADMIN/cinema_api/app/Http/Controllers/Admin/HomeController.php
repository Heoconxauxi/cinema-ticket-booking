<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Phim;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch dashboard stats (replace with your logic)
        $totalRevenue = 39509320; // Example in VND
        $todayRevenue = 153000;  // Updated for 12:32 PM on 22-10-2025
        $totalCustomers = 8;     // Example
        $totalOrders = 27;       // Example

        return view('admin.index', compact('totalRevenue', 'todayRevenue', 'totalCustomers', 'totalOrders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Phim $phim)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Phim $phim)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Phim $phim)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Phim $phim)
    {
        //
    }
}
