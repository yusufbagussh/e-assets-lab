<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Major;
use App\Models\Borrower;
use Illuminate\Http\Request;
use App\Helpers\ApiFormatter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class BorrowerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $paginate = $request->paginate;
        $sort = $request->sortBy;
        $type = $request->sortOrder;

        $data = Borrower::query();

        $data->with('major');

        if ($search) {
            $data->whereHas('major', function ($value) use ($search) {
                $value->where('peminjam_nama', 'LIKE', "%" . $search . "%")
                    ->orWhere('jurusan_nama', 'LIKE', "%" . $search . "%")
                    ->orWhere('peminjam_status', 'LIKE', "%" . $search . "%");
            });
        }

        if ($sort && in_array($sort, ['peminjam_nama', 'jurusan_nama', 'peminjam_status'])) {
            $sortBy = $sort;
        } else {
            $sortBy = 'peminjam_id';
        }

        if ($type && in_array($type, ['ASC', 'DESC'])) {
            $sortOrder = $type;
        } else {
            $sortOrder = 'DESC';
        }

        if ($sort == 'jurusan_nama') {
            $data->orderBy(
                Major::select('jurusan_nama')
                    ->whereColumn('jurusan_id', 'borrowers.peminjam_jurusan'),
                $sortOrder
            );
        } else {
            $data->orderBy($sortBy, $sortOrder);
        }

        // if ($paginate) {
        //     return $data->paginate($paginate);
        // } else {
        //     return $data->paginate(10);
        // }

        if ($paginate == 'all') {
            $jml = count($data->get()->toArray());
            return ApiFormatter::createApi(200, 'Success',  $data->paginate($jml));
        } else if ($paginate) {
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
            'peminjam_no_identitas.required' => 'nomer identitas peminjam wajib diisi',
            'peminjam_nama.required' => 'nama peminjam wajib diisi',
            'peminjam_jurusan.required' => 'jurusan peminjam wajib diisi',
            'peminjam_email.required' => 'email peminjam wajib diisi',
            'peminjam_no_wa.required' => 'nomer WA peminjam wajib diisi',
            'peminjam_status.required' => 'status peminjam wajib diisi',
        ];

        $validator = Validator::make($request->all(), [
            'peminjam_no_identitas' => 'required',
            'peminjam_nama' => 'required',
            'peminjam_jurusan' => 'required',
            'peminjam_email' => 'required',
            'peminjam_no_wa' => 'required',
            'peminjam_status' => 'required',
        ], $message);

        if ($validator->fails()) {
            return ApiFormatter::createApi(422, $validator->errors());
        }

        $borrower = Borrower::create([
            'peminjam_no_identitas' => $request->peminjam_no_identitas,
            'peminjam_nama' => $request->peminjam_nama,
            'peminjam_jurusan' => $request->peminjam_jurusan,
            'peminjam_email' => $request->peminjam_email,
            'peminjam_no_wa' => '0' . $request->peminjam_no_wa,
            'peminjam_status' => $request->peminjam_status,
        ]);

        $data = Borrower::where('peminjam_id', '=', $borrower->peminjam_id)->get();

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
        $data = Borrower::where('peminjam_id', '=', $id)->get();

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
            'peminjam_no_identitas.required' => 'nomer identitas peminjam wajib diisi',
            'peminjam_nama.required' => 'nama peminjam wajib diisi',
            'peminjam_jurusan.required' => 'jurusan peminjam wajib diisi',
            'peminjam_email.required' => 'email peminjam wajib diisi',
            'peminjam_no_wa.required' => 'nomer WA peminjam wajib diisi',
            'peminjam_status.required' => 'status peminjam wajib diisi',
        ];

        $validator = Validator::make($request->all(), [
            'peminjam_no_identitas' => 'required',
            'peminjam_nama' => 'required',
            'peminjam_jurusan' => 'required',
            'peminjam_email' => 'required',
            'peminjam_no_wa' => 'required',
            'peminjam_status' => 'required',
        ], $message);

        if ($validator->fails()) {
            return ApiFormatter::createApi(422, $validator->errors());
        }

        $borrower = Borrower::findOrFail($id);

        if ($request->peminjam_no_wa == $borrower->peminjam_no_wa) {
            $no_wa = $borrower->peminjam_no_wa;
        } else {
            $no_wa = '0' . $request->peminjam_no_wa;
        }

        $borrower->update([
            'peminjam_no_identitas' => $request->peminjam_no_identitas,
            'peminjam_nama' => $request->peminjam_nama,
            'peminjam_jurusan' => $request->peminjam_jurusan,
            'peminjam_email' => $request->peminjam_email,
            'peminjam_no_wa' => $no_wa,
            'peminjam_status' => $request->peminjam_status,
        ]);

        $data = Borrower::where('peminjam_id', '=', $borrower->peminjam_id)->get();

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
        $borrower = Borrower::findOrFail($id);

        $data = $borrower->delete();

        if ($data) {
            return ApiFormatter::createApi(200, 'Success', $data);
        } else {
            return ApiFormatter::createApi(400, 'Failed');
        }
    }
}
