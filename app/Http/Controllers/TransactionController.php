<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Helpers\ApiFormatter;
use Exception;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Transaction::with(['item', 'borrower', 'userCreated', 'userUpdated'])->get();

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
                'transaksi_item' => 'required',
                'transaksi_peminjam' => 'required',
                'transaksi_jumlah' => 'required',
                'transaksi_tgl_pinjam' => 'required',
                'transaksi_lama_pinjam' => 'required',
                'transaksi_tgl_kembali' => 'required',
                'transaksi_denda' => 'required',
                'transaksi_status' => 'required',
            ]);

            $transaction = Transaction::create([
                'transaksi_item' => $request->transaksi_item,
                'transaksi_peminjam' => $request->transaksi_peminjam,
                'transaksi_jumlah' => $request->transaksi_jumlah,
                'transaksi_tgl_pinjam' => $request->transaksi_tgl_pinjam,
                'transaksi_lama_pinjam' => $request->transaksi_lama_pinjam,
                'transaksi_tgl_kembali' => $request->transaksi_tgl_kembali,
                'transaksi_denda' => $request->transaksi_denda,
                'transaksi_status' => $request->transaksi_status,
                'created_by' => $request->created_by,
                'updated_by' => $request->updated_by,
            ]);

            $data = Transaction::where('transaksi_id', '=', $transaction->transaksi_id)->get();

            if ($data) {
                return ApiFormatter::createApi(200, 'Success', $data);
            } else {
                return ApiFormatter::createApi(400, 'Failed', $data);
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
        $data = Transaction::where('transaksi_id', '=', $id)->get();

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
                'transaksi_item' => 'required',
                'transaksi_peminjam' => 'required',
                'transaksi_jumlah' => 'required',
                'transaksi_tgl_pinjam' => 'required',
                'transaksi_lama_pinjam' => 'required',
                'transaksi_tgl_kembali' => 'required',
                'transaksi_denda' => 'required',
                'transaksi_status' => 'required',
            ]);

            $transaction = Transaction::findOrFail($id);

            $transaction->update([
                'transaksi_item' => $request->transaksi_item,
                'transaksi_peminjam' => $request->transaksi_peminjam,
                'transaksi_jumlah' => $request->transaksi_jumlah,
                'transaksi_tgl_pinjam' => $request->transaksi_tgl_pinjam,
                'transaksi_lama_pinjam' => $request->transaksi_lama_pinjam,
                'transaksi_tgl_kembali' => $request->transaksi_tgl_kembali,
                'transaksi_denda' => $request->transaksi_denda,
                'transaksi_status' => $request->transaksi_status,
                'created_by' => $request->created_by,
                'updated_by' => $request->updated_by,
            ]);

            $data = Transaction::where('transaksi_id', '=', $transaction->transaksi_id)->get();

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
        $transaction = Transaction::findOrFail($id);

        $data = $transaction->delete();

        if ($data) {
            return ApiFormatter::createApi(200, 'Success', $data);
        } else {
            return ApiFormatter::createApi(400, 'Failed');
        }
    }
}
