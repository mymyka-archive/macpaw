<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\StoreContributorRequest;
use App\Http\Requests\UpdateContributorRequest;
use App\Models\Contributor;
use App\Http\Controllers\Controller;

class ContributorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Contributor $contributor)
    {
        //
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
