<?php

namespace App\Http\Controllers;

use App\Http\Resources\MajorResource;
use Illuminate\Http\Request;
use App\Models\Major;

class MajorController extends Controller
{
    public function index()
    {
        return MajorResource::collection(Major::all());
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $input = $request->all();

        $Major = Major::create($input);

        return new MajorResource($Major);
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $Major = Major::find($id);

        $Major->update($request->all());

        return new MajorResource($Major);
    }

    public function destroy($id)
    {
        $Major = Major::find($id);

        $Major->delete();

        return response()->json(null);
    }
}
