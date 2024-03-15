<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use Illuminate\Http\Request;

class ProviderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //Get Providers
        $providers = Provider::all();

        //Return Providers
        return response()->json($providers, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //Create Provider
        $provider = Provider::create([
            'name' => $request->name,
            'rif' => $request->rif,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        //Return Provider
        return response()->json([
            'message' => 'Provider created successfully',
            'provider' => $provider
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Provider $provider)
    {
        //Return Provider
        return response()->json($provider, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Provider $provider)
    {
        //Update Provider
        $provider->update([
            'name' => $request->name,
            'rif' => $request->rif,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        //Return Provider
        return response()->json([
            'message' => 'Provider updated successfully',
            'provider' => $provider
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Provider $provider)
    {
        //
    }
}
