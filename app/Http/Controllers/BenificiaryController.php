<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBenificiaryRequest;
use App\Http\Requests\UpdateBenificiaryRequest;
use App\Models\Benificiary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 *
 */
class BenificiaryController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreBenificiaryRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();
        Log::info($data);
        $result = Benificiary::create($data);

        return response()->json([
            'message' => 'Benificiary created successfully',
            'id' => $result['id']
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Benificiary  $benificiary
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Benificiary $benificiary)
    {
        return response()->json([
            'data' => $benificiary,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Benificiary  $benificiary
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateBenificiaryRequest $request, Benificiary $benificiary)
    {
        $benificiary->update($request->validated());

        return response()->json([
            'message' => 'Benificiary updated successfully',
            'id' => $benificiary['id']
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Benificiary  $benificiary
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Benificiary $benificiary)
    {
        $benificiary->delete();
        return response()->json(['message' => 'Benificiary deleted successfully']);
    }


    /**
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRetailersByBenificiaryId(int $id)
    {
        $benificiary = Benificiary::findOrFail($id);
        $retailers = $benificiary->retailers()->get();
        return response()->json($retailers);
    }


    /**
     * @param Request $request
     * @param Benificiary $benificiary
     * @return \Illuminate\Http\JsonResponse
     */
    public function addRetailers(Request $request, Benificiary $benificiary)
    {
        $validatedData = $request->validate([
            'retailer_ids' => 'required|array',
            'retailer_ids.*' => 'exists:retailers,id'
        ]);

        $benificiary->retailers()->sync($validatedData['retailer_ids']);

        return response()->json([
            'message' => 'Retailers added to benificiary successfully'
        ]);
    }
}
