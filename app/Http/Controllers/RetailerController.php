<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRetailerRequest;
use App\Http\Requests\UpdateRetailerRequest;
use App\Models\Retailer;

class RetailerController extends Controller
{

    /**
     * Store a newly created retail in storage.
     *
     * @param \App\Http\Requests\StoreRetailerRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRetailerRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();
        $result = Retailer::create($data);

        return response()->json([
            'message' => 'Retailer created successfully',
            'id' => $result['id']
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Retailer  $retailer
     * @return \Illuminate\Http\JsonResponse
     */
        public function show(Retailer $retailer)
    {
        return response()->json([
            'data' => $retailer,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateRetailerRequest  $request
     * @param  \App\Models\Retailer  $retailer
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRetailerRequest $request, Retailer $retailer)
    {
        $retailer->update($request->validated());

        return response()->json([
            'message' => 'Retailer updated successfully',
            'id' => $retailer['id']
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Retailer  $retailer
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Retailer $retailer)
    {
        $retailer->delete();
        return response()->json(['message' => 'Retailer deleted successfully']);
    }
}
