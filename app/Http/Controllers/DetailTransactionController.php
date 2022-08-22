<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DetailTransaction;
use App\Helpers\ApiFormatter;
use Exception;

class DetailTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = DetailTransaction::with(['transaction', 'user'])->get();

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
                'detail_transaksi_id' => 'required',
                'detail_transaksi_user' => 'required',
                'detail_transaksi_tgl_pengembalian' => 'required',
                'detail_transaksi_denda' => 'required',
            ]);

            $detailTransaction = DetailTransaction::create([
                'detail_transaksi_id' => $request->detail_transaksi_id,
                'detail_transaksi_user' => $request->detail_transaksi_user,
                'detail_transaksi_tgl_pengembalian' => $request->detail_transaksi_tgl_pengembalian,
                'detail_transaksi_denda' => $request->detail_transaksi_denda,
            ]);

            $data = DetailTransaction::where('detail_transaksi_id', '=', $detailTransaction->detail_transaksi_id)->get();

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
        $data = DetailTransaction::where('detail_transaksi_id', '=', $id)->get();

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
                'detail_transaksi_id' => 'required',
                'detail_transaksi_user' => 'required',
                'detail_transaksi_tgl_pengembalian' => 'required',
                'detail_transaksi_denda' => 'required',
            ]);

            $detailTransaction = DetailTransaction::findOrFail($id);

            $detailTransaction->update([
                'detail_transaksi_id' => $request->detail_transaksi_id,
                'detail_transaksi_user' => $request->detail_transaksi_user,
                'detail_transaksi_tgl_pengembalian' => $request->detail_transaksi_tgl_pengembalian,
                'detail_transaksi_denda' => $request->detail_transaksi_denda,
            ]);

            $data = DetailTransaction::where('detail_transaksi_id', '=', $detailTransaction->detail_transaksi_id)->get();

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
        $detailTransaction = DetailTransaction::findOrFail($id);

        $data = $detailTransaction->delete();

        if ($data) {
            return ApiFormatter::createApi(200, 'Success', $data);
        } else {
            return ApiFormatter::createApi(400, 'Failed');
        }
    }
}
