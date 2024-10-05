<?php

namespace App\Http\Controllers\v1\Admin;

use App\Http\Controllers\BaseController;
use App\Models\Level;
use Illuminate\Http\Request;

class LevelController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $levels = Level::all();
        return $this->sendResponse($levels, "Levels retrieved successfully");
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
    public function show(Level $level)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Level $level)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Level $level)
    {
        //
    }
}
