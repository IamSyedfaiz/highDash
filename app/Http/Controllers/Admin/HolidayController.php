<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $holidays = \App\Models\Holiday::orderBy('date')->paginate(15);
        return view('admin.holidays.index', compact('holidays'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'locations' => 'required|string',
            'description' => 'nullable|string'
        ]);
        \App\Models\Holiday::create($request->all());
        return back()->with('success', 'Holiday added successfully');
    }

    public function destroy(\App\Models\Holiday $holiday)
    {
        $holiday->delete();
        return back()->with('success', 'Holiday deleted');
    }
}
