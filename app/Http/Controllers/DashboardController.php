<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\ApiFormatter;
use Illuminate\Support\Carbon;


class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $totalItems = DB::table('items')->count();
        $totalUsers = DB::table('users')->count();
        $totalBorrowers = DB::table('borrowers')->count();
        $totalTransactions = DB::table('transactions')->count();

        $itemTidakDipinjam = DB::table('items')->sum('item_jumlah');
        $itemDipinjam = DB::table('transactions')->sum('transaksi_jumlah');
        $total = $itemTidakDipinjam + $itemDipinjam;

        $day = Carbon::now()->format('d');
        $month = Carbon::now()->format('m');
        $year = Carbon::now()->format('Y');
        $totalTransactionByMonth = DB::table('transactions')->whereMonth('transaksi_tgl_pinjam', $month)->count();
        $data = [
            'totalTransactions' => $totalTransactions,
            'totalBorrowers' => $totalBorrowers,
            'totalUsers' => $totalUsers,
            'totalItems' => $totalItems,
            'itemTidakDipinjam' => $itemTidakDipinjam,
            'itemDipinjam' => $itemDipinjam,
            'total' => $total,
            'totalTransactionByMonth' => $totalTransactionByMonth,

        ];

        return ApiFormatter::createApi(200, 'Success', $data);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
