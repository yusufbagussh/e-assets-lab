<?php

namespace App\Http\Controllers;

use DateTime;
use DatePeriod;
use DateInterval;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Helpers\ApiFormatter;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;


class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $start_month = $request->start_month;
        $end_month = $request->end_month;
        $start_year = $request->start_year;
        $end_year = $request->end_year;


        if ($start_month && $start_year && $end_month && $end_year) {
            $total_date_permonth =  cal_days_in_month(CAL_GREGORIAN, $end_month, $end_year);
            $start_date = new DateTime("$start_year-$start_month-01");
            $end_date = new DateTime("$end_year-$end_month-$total_date_permonth");
        } else {
            $year = ((new DateTime())->format('Y'));
            $start_date = "$year-01-01";
            $start_date = new DateTime($start_date);
            $end_date = new DateTime();

            // $end = (new DateTime())->modify('+1 month');
            // $firstMonth = ((new DateTime())->format('m')) - 1;
            // $start = (new DateTime())->modify("-$firstMonth month")
        }

        $interval = DateInterval::createFromDateString('1 month');
        $period   = new DatePeriod(
            $start_date,
            $interval,
            $end_date,
        );

        $listYearMonth = [];
        //iterasi data perodiode ke periode
        foreach ($period as $dt) {
            array_push($listYearMonth, $dt->format("Y-m"));
        }
        // return $listYearMonth;

        $transactions = Transaction::all()->toArray();
        // return $transactions;
        $totalTransaksi = [];
        foreach ($listYearMonth as $yearMonth) {
            $totalTransaksiByMonth = [];
            foreach ($transactions as $transaction) {
                $newTglPinjam = (new DateTime($transaction['transaksi_tgl_pinjam']))->format('Y-m');
                if ($newTglPinjam == $yearMonth) {
                    array_push($totalTransaksiByMonth, $transaction);
                }
            }
            $jumlah = count($totalTransaksiByMonth);
            array_push($totalTransaksi, $jumlah);
        }

        // return $totalTransaksi;
        // return $listYearMonth;


        $totalItems = DB::table('items')->count();
        $totalUsers = DB::table('users')->count();
        $totalBorrowers = DB::table('borrowers')->count();
        $totalTransactions = DB::table('transactions')->count();

        $item_id = $request->item_id;
        if ($item_id) {
            $itemTidakDipinjam = DB::table('items')->where('item_id', $item_id)->sum('item_jumlah');
            $itemDipinjam = DB::table('transactions')->where('transaksi_item', $item_id)->where('transaksi_status', 'Dipinjam')->sum('transaksi_jumlah');
        } elseif ($item_id == "" || $item_id == null || $item_id == "All") {
            $itemTidakDipinjam = DB::table('items')->sum('item_jumlah');
            $itemDipinjam = DB::table('transactions')->where('transaksi_status', 'Dipinjam')->sum('transaksi_jumlah');
        }
        $total = $itemTidakDipinjam + $itemDipinjam;

        // return $data = [$itemDipinjam, $itemTidakDipinjam];

        $day = Carbon::now()->format('d');
        $month = Carbon::now()->format('m');
        $year = Carbon::now()->format('Y');
        $totalTransactionByMonth = DB::table('transactions')->whereMonth('transaksi_tgl_pinjam', $month)->count();
        // $bulan = DB::table('transactions')->select(DB::raw('count(transaksi_id) as totalPerBulan, MONTHNAME(transaksi_tgl_pinjam), YEAR(transaksi_tgl_pinjam) year, MONTH(transaksi_tgl_pinjam) month'))->groupBy('year', 'month')->get();
        $bulan = DB::table('transactions')->select(DB::raw('count(transaksi_id) as total, MONTHNAME(transaksi_tgl_pinjam)'))->groupBY('transaksi_tgl_pinjam')->get();

        // $end = (new DateTime())->modify('+1 month');
        // $firstMonth = ((new DateTime())->format('m')) - 1;
        // $start = (new DateTime())->modify("-$firstMonth month");
        // // return [$start, $end];

        // //membuat interval date
        // $interval = DateInterval::createFromDateString('1 month');
        // $period   = new DatePeriod(
        //     $start,
        //     $interval,
        //     $end
        // );

        // $listYearMonth = [];
        // //iterasi data perodiode ke periode
        // foreach ($period as $dt) {
        //     array_push($listYearMonth, $dt->format("Y-m"));
        // }
        // // return $listYearMonth;

        // $transactions = Transaction::all()->toArray();
        // // return $transactions;
        // $totalTransaksi = [];
        // foreach ($listYearMonth as $yearMonth) {
        //     $totalTransaksiByMonth = [];
        //     foreach ($transactions as $transaction) {
        //         $newTglPinjam = (new DateTime($transaction['transaksi_tgl_pinjam']))->format('Y-m');
        //         if ($newTglPinjam == $yearMonth) {
        //             array_push($totalTransaksiByMonth, $transaction);
        //         }
        //     }
        //     $jumlah = count($totalTransaksiByMonth);
        //     array_push($totalTransaksi, $jumlah);
        // }

        // return $totalTransaksi;
        // return $listYearMonth;

        $data = [
            //Dashboard Atas
            'totalTransactions' => $totalTransactions,
            'totalBorrowers' => $totalBorrowers,
            'totalUsers' => $totalUsers,
            'totalItems' => $totalItems,
            //Dashboaard Grafik 1
            'itemTidakDipinjam' => $itemTidakDipinjam,
            'itemDipinjam' => $itemDipinjam,
            'total' => $total,
            //Dashboard Grafik 2
            'totalTransactionByMonth' => $totalTransactionByMonth,
            'bulan' => $bulan,
            //Dashboard Grafik 3
            'listDateTransaksi' => $listYearMonth,
            'listTotalTransaksi' => $totalTransaksi,

        ];

        return ApiFormatter::createApi(200, 'Success', $data);
    }


    public function getDataTransaksiByDate(Request $request)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        if ($start_date && $end_date) {
            $start_date = new DateTime($start_date);
            $end_date = (new DateTime($end_date))->modify('+1 day');
            $interval = DateInterval::createFromDateString('1 day');
            $period = new DatePeriod(
                $start_date,
                $interval,
                $end_date
            );

            $listDate = [];

            foreach ($period as $date) {
                array_push($listDate, $date->format('Y-m-d'));
            }
        } else {
            $end_date = new DateTime();
            $start_date = (new DateTime())->modify('-1 month')->format('Y-m') . '-' . 01;
            $start_date = new DateTime($start_date);
            $interval = DateInterval::createFromDateString('1 day');
            $period = new DatePeriod(
                $start_date,
                $interval,
                $end_date
            );

            $listDate = [];

            foreach ($period as $date) {
                array_push($listDate, $date->format('Y-m-d'));
            }
        }

        $transactions = Transaction::all()->toArray();

        $totalTransaksi = [];
        foreach ($listDate as $date) {
            $totalTransaksiByDate = [];
            foreach ($transactions as $transaction) {
                // $newTglPinjam = (new DateTime($transaction['transaksi_tgl_pinjam']))->format('Y-m');
                if ($transaction['transaksi_tgl_pinjam'] == $date) {
                    array_push($totalTransaksiByDate, $transaction);
                }
            }
            $jumlah = count($totalTransaksiByDate);
            array_push($totalTransaksi, $jumlah);
        }
        $data = [
            'listDateTransaksi' => $listDate,
            'listTotalTransaksi' => $totalTransaksi,
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
