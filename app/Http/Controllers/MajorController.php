<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Major;
use Illuminate\Http\Request;
use App\Helpers\ApiFormatter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class MajorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $data = Major::all();

        $search = $request->search;
        $paginate = $request->paginate;
        $sort = $request->sortBy;
        $type = $request->sortOrder;

        $data = Major::query();

        if ($search) {
            $data->where('jurusan_nama', 'LIKE', '%' . $search . '%')
                ->orWhere('jurusan_fakultas', 'LIKE', '%' . $search . '%');
        }

        if ($sort && in_array($sort, ['jurusan_nama', 'jurusan_fakultas'])) {
            $sortBy = $sort;
        } else {
            $sortBy = 'jurusan_id';
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
        } else if ($paginate) {
            return ApiFormatter::createApi(200, 'Success',  $data->paginate($paginate));
        } else {
            return ApiFormatter::createApi(200, 'Success', $data->paginate(10));
        }

        // if ($data) {
        //     return ApiFormatter::createApi(200, 'Success', $data);
        // } else {
        //     return ApiFormatter::createApi(400, 'Failed');
        // }
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
            'jurusan_nama.required' => 'nama jurusan wajib diisi',
            'jurusan_fakultas.required' => 'nama fakultas wajib diisi',
        ];

        $validator = Validator::make($request->all(), [
            'jurusan_nama' => 'required',
            'jurusan_fakultas' => 'required'
        ], $message);

        if ($validator->fails()) {
            return ApiFormatter::createApi(422, $validator->errors());
        }

        $major = Major::create([
            'jurusan_nama' => $request->jurusan_nama,
            'jurusan_fakultas' => $request->jurusan_fakultas,
        ]);

        $data = Major::where('jurusan_id', '=', $major->jurusan_id)->get();

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
        $data = Major::where('jurusan_id', '=', $id)->get();

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
            'jurusan_nama.required' => 'nama jurusan wajib diisi',
            'jurusan_fakultas.required' => 'nama fakultas wajib diisi',
        ];

        $validator = Validator::make($request->all(), [
            'jurusan_nama' => 'required',
            'jurusan_fakultas' => 'required'
        ], $message);

        if ($validator->fails()) {
            return ApiFormatter::createApi(422, $validator->errors());
        }

        $major = Major::findOrFail($id);

        $major->update([
            'jurusan_nama' => $request->jurusan_nama,
            'jurusan_fakultas' => $request->jurusan_fakultas,
        ]);

        $data = Major::where('jurusan_id', '=', $major->jurusan_id)->get();

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
        $major = Major::findOrFail($id);

        $data = $major->delete();

        if ($data) {
            return ApiFormatter::createApi(200, 'Success', $data);
        } else {
            return ApiFormatter::createApi(400, 'Failed');
        }
    }
}
