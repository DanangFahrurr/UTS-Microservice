<?php

namespace App\Http\Controllers;

use App\Models\Hiker;
use Illuminate\Http\Request;

class HikerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $hikers = Hiker::with('medicalStatus')->paginate(15);
        return response()->json($hikers);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:hikers',
            'phone'      => 'required|string|max:20',
            'nik'        => 'required|string|unique:hikers',
            'birth_date' => 'required|date',
            'gender'     => 'required|in:male,female',
            'address'    => 'required|string',
            'emergency_contact_name'  => 'required|string',
            'emergency_contact_phone' => 'required|string|max:20',
        ]);

        $hiker = Hiker::create($validated);

        return response()->json([
            'message' => 'Pendaki berhasil didaftarkan',
            'data' => $hiker
        ], 201);
    }

    // GET /api/hikers/{id}
    public function show(Hiker $hiker)
    {
        return response()->json(
            $hiker->load(['medicalStatus', 'hikingHistories'])
        );
    }

    //PUT /api/hikers/{id}
    public function update(Request $request, Hiker $hiker)
    {
        $validated = $request->validate([
            'name'    => 'sometimes|string|max:255',
            'phone'   => 'sometimes|string|max:20',
            'address' => 'sometimes|string',
            'emergency_contact_name'  => 'sometimes|string',
            'emergency_contact_phone' => 'sometimes|string|max:20',
        ]);

        $hiker->update($validated);

        return response()->json([
            'message' => 'Data pendaki berhasil diupdate',
            'data'    => $hiker
        ]);
    }

    // DELETE /api/hikers/{id}
    public function destroy(Hiker $hiker)
    {
        $hiker->delete();

        return response()->json([
            'message' => 'Data pendaki dihapus'
        ], 200);
    }

    public function create()
    {
        return view('hikers.create');
    }
}
