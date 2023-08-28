<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\StoreContributorRequest;
use App\Http\Requests\UpdateContributorRequest;
use App\Models\Contributor;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\ContributorCollection;
use App\Http\Resources\V1\ContributorResource;
use Illuminate\Support\Facades\DB;

class ContributorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // ORM
        // return new ContributorCollection(Contributor::all());
        // SQL
        return DB::select('SELECT id, user_name, amount FROM contributors');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreContributorRequest $request)
    {
        // ORM
        // return new ContributorResource(Contributor::create($request->all()));
        // SQL
        $result = DB::insert('INSERT INTO contributors (collection_id, user_name, amount) VALUES (?, ?, ?)', [
            $request->collectionId,
            $request->userName,
            $request->amount,
        ]);
        return ($result) ? response()->json(['message' => 'Success'], 200) : response()->json(['error' => 'Something went wrong'], 500);
    }

    /**
     * Display the specified resource.
     */
    public function show(Contributor $contributor)
    {
        // ORM
        // return new ContributorResource($contributor);
        // SQL
        $result = DB::select('SELECT id, user_name, amount FROM contributors WHERE id = ?', [$contributor->id]);
        return $result[0];
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateContributorRequest $request, Contributor $contributor)
    {
        $contributor->update($request->all());
        return new ContributorResource($contributor);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contributor $contributor)
    {
        // ORM
        // $contributor->delete();
        // return response()->noContent();
        // SQL
        $result = DB::delete('DELETE FROM contributors WHERE id = ?', [$contributor->id]);
        return response()->noContent();
    }
}
