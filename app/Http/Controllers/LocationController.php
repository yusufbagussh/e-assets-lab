<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Location;
use Illuminate\Http\Request;
use App\Helpers\ApiFormatter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $data = Location::all();
        $search = $request->search;
        $paginate = $request->paginate;
        $sort = $request->sortBy;
        $type = $request->sortOrder;

        $data = Location::query();

        if ($search) {
            $data->where('lokasi_nama', 'LIKE', '%' . $search . '%');
        }

        if ($sort && in_array($sort, ['lokasi_nama', 'lokasi_id'])) {
            $sortBy = $sort;
        } else {
            $sortBy = 'lokasi_id';
        }

        if ($type && in_array($type, ['ASC', 'DESC'])) {
            $sortOrder = $type;
        } else {
            $sortOrder = 'DESC';
        }

        $data->orderBy($sortBy, $sortOrder);

        if ($paginate == 'all') {
            $jml = count($data->get()->toArray());
            return ApiFormatter::createApi(200, 'Success',  $data->paginate($jml));
        } else if ($paginate != 'all') {
            return ApiFormatter::createApi(200, 'Success',  $data->paginate($paginate));
        } else {
            return ApiFormatter::createApi(200, 'Success', $data->paginate(10));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $message = [
            'lokasi_nama.required' => 'nama lokasi wajib diisi',
        ];

        $validator = Validator::make($request->all(), [
            'lokasi_nama' => 'required',
        ], $message);

        if ($validator->fails()) {
            return ApiFormatter::createApi(422, $validator->errors());
        }

        $location = Location::create([
            'lokasi_nama' => $request->lokasi_nama,
        ]);

        $data = Location::where('lokasi_id', '=', $location->lokasi_id)->get();

        if ($data) {
            return ApiFormatter::createApi(201, 'Success', $data);
        } else {
            return ApiFormatter::createApi(400, 'Failed');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Location::where('lokasi_id', '=', $id)->get();

        if ($data) {
            return ApiFormatter::createApi(200, 'Success', $data);
        } else {
            return ApiFormatter::createApi(400, 'Failed');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $message = [
            'lokasi_nama.required' => 'nama lokasi wajib diisi',
        ];

        $validator = Validator::make($request->all(), [
            'lokasi_nama' => 'required',
        ], $message);

        if ($validator->fails()) {
            return ApiFormatter::createApi(422, $validator->errors());
        }

        $location = Location::findOrFail($id);

        $location->update([
            'lokasi_nama' => $request->lokasi_nama,
        ]);


        $data = Location::where('lokasi_id', '=', $location->lokasi_id)->get();
        if ($data) {
            return ApiFormatter::createApi(201, 'Success', $data);
        } else {
            return ApiFormatter::createApi(400, 'Failed');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $location = Location::findOrFail($id);

        $data = $location->delete();

        if ($data) {
            return ApiFormatter::createApi(200, 'Success', $data);
        } else {
            return ApiFormatter::createApi(400, 'Failed');
        }
    }
}
