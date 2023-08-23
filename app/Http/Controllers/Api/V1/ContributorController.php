<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\StoreContributorRequest;
use App\Http\Requests\UpdateContributorRequest;
use App\Models\Contributor;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\ContributorCollection;
use App\Http\Resources\V1\ContributorResource;

class ContributorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return new ContributorCollection(Contributor::all());
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
    public function store(StoreContributorRequest $request)
    {
        return new ContributorResource(Contributor::create($request->validated()));
    }

    /**
     * Display the specified resource.
     */
    public function show(Contributor $contributor)
    {
        return new ContributorResource($contributor);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contributor $contributor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateContributorRequest $request, Contributor $contributor)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contributor $contributor)
    {
        //
    }
}
