<?php

namespace App\Http\Controllers\Api\V1;

use App\Commands\V1\Collection\FilterCollectionCommand;
use App\Http\Requests\StoreCollectionRequest;
use App\Http\Requests\UpdateCollectionRequest;
use App\Http\Requests\FilterCollectionRequest;
use App\Models\Collection;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\CollectionCollection;
use App\Http\Resources\V1\CollectionResource;
use Illuminate\Support\Facades\DB;

class CollectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // ORM
        // return new CollectionCollection(Collection::all());
        // SQL
        return DB::select('SELECT id, title, description, target_amount, link FROM collections');
    }

    public function filter(FilterCollectionRequest $request)
    {
        $result = FilterCollectionCommand::call($request);
        return $result->data;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCollectionRequest $request)
    {
        // ORM
        // return new CollectionResource(Collection::create($request->all()));
        // SQL

        $result = DB::insert('INSERT INTO collections (title, description, target_amount, link) VALUES (?, ?, ?, ?)', [
            $request->title,
            $request->description,
            $request->targetAmount,
            $request->link
        ]);
        return ($result) ? response()->json(['message' => 'Success'], 200) : response()->json(['error' => 'Something went wrong'], 500);
    }

    /**
     * Display the specified resource.
     */
    public function show(Collection $collection)
    {   
        // ORM
        // return new CollectionResource($collection->loadMissing('contributors'));
        // SQL
        $result = DB::select('
            SELECT id, title, description, target_amount, link
            FROM collections 
            WHERE id = ?', [$collection->id])[0];
        $contributors = DB::select('
            SELECT id, user_name, amount
            FROM contributors 
            WHERE collection_id = ?', [$collection->id]);
        return response()->json([
            'data' => [
                'collection' => [
                    'id' => $result->id,
                    'title' => $result->title,
                    'description' => $result->description,
                    'targetAmount' => $result->target_amount,
                    'link' => $result->link,
                    'contributors' => $contributors
                ]
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCollectionRequest $request, Collection $collection)
    {
        // ORM
        $collection->update($request->all());
        return new CollectionResource($collection);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Collection $collection)
    {
        // ORM
        // $collection->delete();
        // return response()->noContent();
        // SQL
        $result = DB::delete('DELETE FROM collections WHERE id = ?', [$collection->id]);
        return ($result) ? response()->json(['message' => 'Success'], 200) : response()->json(['error' => 'Something went wrong'], 500);
    }
}
