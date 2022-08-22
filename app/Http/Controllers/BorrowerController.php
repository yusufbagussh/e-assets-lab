<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Borrower;
use App\Helpers\ApiFormatter;
use Exception;

class BorrowerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Borrower::with('major')->get();

        if ($data) {
            return ApiFormatter::createApi(200, 'Success', $data);
        } else {
            return ApiFormatter::createApi(400, 'Failed');
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
        try {
            $request->validate([
                'peminjam_no_identitas' => 'required',
                'peminjam_nama' => 'required',
                'peminjam_jurusan' => 'required',
                'peminjam_email' => 'required',
                'peminjam_no_wa' => 'required',
                'peminjam_status' => 'required',
            ]);

            $borrower = Borrower::create([
                'peminjam_no_identitas' => $request->peminjam_no_identitas,
                'peminjam_nama' => $request->peminjam_nama,
                'peminjam_jurusan' => $request->peminjam_jurusan,
                'peminjam_email' => $request->peminjam_email,
                'peminjam_no_wa' => $request->peminjam_no_wa,
                'peminjam_status' => $request->peminjam_status,
            ]);

            $data = Borrower::where('peminjam_id', '=', $borrower->peminjam_id)->get();

            if ($data) {
                return ApiFormatter::createApi(200, 'Success', $data);
            } else {
                return ApiFormatter::createApi(400, 'Failed');
            }
        } catch (Exception $error) {
            return ApiFormatter::createApi(400, 'Failed', $error);
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
        try {
            $request->validate([
                'peminjam_no_identitas' => 'required',
                'peminjam_nama' => 'required',
                'peminjam_jurusan' => 'required',
                'peminjam_email' => 'required',
                'peminjam_no_wa' => 'required',
                'peminjam_status' => 'required',
            ]);

            $borrower = Borrower::findOrFail($id);

            $borrower->update([
                'peminjam_no_identitas' => $request->peminjam_no_identitas,
                'peminjam_nama' => $request->peminjam_nama,
                'peminjam_jurusan' => $request->peminjam_jurusan,
                'peminjam_email' => $request->peminjam_email,
                'peminjam_no_wa' => $request->peminjam_no_wa,
                'peminjam_status' => $request->peminjam_status,
            ]);

            $data = Borrower::where('peminjam_id', '=', $borrower->peminjam_id)->get();

            if ($data) {
                return ApiFormatter::createApi(200, 'Success', $data);
            } else {
                return ApiFormatter::createApi(400, 'Failed');
            }
        } catch (Exception $error) {
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
