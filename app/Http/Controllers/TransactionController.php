<?php

namespace App\Http\Controllers;

use DateTime;
use DatePeriod;
use DateInterval;
use Exception;
use App\Models\Item;
use App\Models\User;
use App\Models\Borrower;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Helpers\ApiFormatter;
use Illuminate\Support\Facades\DB;
use App\Exports\TransactionsExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Calculation\Statistical\Averages\Mean;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $data = Transaction::with(['item', 'borrower', 'userCreated', 'userUpdated'])->get();

        $search = $request->search;
        $paginate = $request->paginate;
        $sort = $request->sortBy;
        $type = $request->sortOrder;
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        $data = Transaction::query();

        $data->with(['item', 'borrower', 'userCreated', 'userUpdated']);

        if ($start_date || $end_date) {
            $data->where(function ($value) use ($start_date, $end_date) {
                $value->whereBetween('transaksi_tgl_pinjam', [$start_date, $end_date]);
            });
        } else {
            // $this_year = (new DateTime())->format('Y');
            // $this_month = (new DateTime())->format('m');
            // $totalDayEndMonth = cal_days_in_month(CAL_GREGORIAN, $this_month, $this_year);
            // $finish_date = "$this_year-$this_month-$totalDayEndMonth";
            $finish_date = (new DateTime())->modify('+1 day')->format('Y-m-d');

            $last_month = ((new DateTime())->modify('-1 month'))->format('Y-m');
            $first_date = "$last_month-01";

            // $first_date = ((new DateTime())->modify('-2 month'))->format('Y-m-d');
            // dd($finish_date);

            $data->whereBetween('transaksi_tgl_pinjam', [$first_date, $finish_date]);
        }

        if ($search) {
            $data->whereHas('item', function ($value) use ($search) {
                $value->where('item_nama', 'LIKE', "%" . $search . "%")
                    ->orWhere('transaksi_status', 'LIKE', "%" . $search . "%");
            })
                // ->with(['item' => function ($value) use ($search) {
                //     $value->where('item_nama', 'LIKE', "%" . $search . "%")
                //         ->orWhere('transaksi_status', 'LIKE', "%" . $search . "%");
                // }])
                ->orWhereHas('borrower', function ($value) use ($search) {
                    $value->where('peminjam_nama', 'LIKE', "%" . $search . "%")
                        ->orWhere('transaksi_status', 'LIKE', "%" . $search . "%");
                });
        }

        if ($sort && in_array($sort, ['item_nama', 'peminjam_nama', 'transaksi_status', 'transaksi_tgl_pinjam', 'transaksi_lama_pinjam', 'transaksi_jumlah'])) {
            $sortBy = $sort;
        } else {
            $sortBy = 'transaksi_id';
        }

        if ($type && in_array($type, ['ASC', 'DESC'])) {
            $sortOrder = $type;
        } else {
            $sortOrder = 'DESC';
        }

        if ($sort == 'item_nama') {
            $data->orderBy(Item::select('item_nama')->whereColumn('item_id', 'transactions.transaksi_item'), $sortOrder);
        } elseif ($sort == 'peminjam_nama') {
            $data->orderBy(Borrower::select('peminjam_nama')->whereColumn('peminjam_id', 'transactions.transaksi_peminjam'), $sortOrder);
        } else {
            $data->orderBy($sortBy, $sortOrder);
        }

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
            'transaksi_item.required' => 'nama item wajib diisi',
            'transaksi_peminjam.required' => 'peminjam wajib diisi',
            'transaksi_jumlah.required' => 'jumlah peminjaman wajib diisi',
            'transaksi_tgl_pinjam.required' => 'tanggal peminjaman wajib diisi',
            'transaksi_lama_pinjam.required' => 'lama peminjaman wajib diisi',
            'transaksi_tgl_kembali.required' => 'tanggal pengembalian wajib diisi',
            'transaksi_status.required' => 'status peminjaman wajib diisi',
        ];

        $validator = Validator::make($request->all(), [
            'transaksi_item' => 'required',
            'transaksi_peminjam' => 'required',
            'transaksi_jumlah' => 'required',
            'transaksi_tgl_pinjam' => 'required',
            'transaksi_lama_pinjam' => 'required',
            'transaksi_tgl_kembali' => 'required',
            'transaksi_status' => 'required',
            'created_by' => 'required'
        ], $message);

        if ($validator->fails()) {
            return ApiFormatter::createApi(422, $validator->errors());
        }

        //get data item
        $itemDipinjam = Item::find($request->transaksi_item);

        //hitung item tersisa
        $itemTersisa = $itemDipinjam->item_jumlah - $request->transaksi_jumlah;

        //update data item
        $itemDipinjam->item_jumlah = $itemTersisa;
        $itemDipinjam->save();
        // return $itemDipinjam;

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
            return ApiFormatter::createApi(201, 'Success', $data);
        } else {
            return ApiFormatter::createApi(400, 'Failed', $data);
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
        // $message = [
        //     'transaksi_item.required' => 'nama item wajib diisi',
        //     'transaksi_peminjam.required' => 'peminjam wajib diisi',
        //     'transaksi_jumlah.required' => 'jumlah peminjaman wajib diisi',
        //     'transaksi_tgl_pinjam.required' => 'tanggal peminjaman wajib diisi',
        //     'transaksi_lama_pinjam.required' => 'lama peminjaman wajib diisi',
        //     'transaksi_tgl_kembali.required' => 'tanggal pengembalian wajib diisi',
        //     'transaksi_status.required' => 'status peminjaman wajib diisi',
        // ];

        // $validator = Validator::make($request->all(), [
        //     'transaksi_item' => 'required',
        //     'transaksi_peminjam' => 'required',
        //     'transaksi_jumlah' => 'required',
        //     'transaksi_tgl_pinjam' => 'required',
        //     'transaksi_lama_pinjam' => 'required',
        //     'transaksi_tgl_kembali' => 'required',
        //     'transaksi_status' => 'required',
        //     'updated_by' => 'required',
        // ], $message);

        // if ($validator->fails()) {
        //     return ApiFormatter::createApi(422, $validator->errors());
        // }

        //cek apakah status sudah dikembalikan atau belum
        if ($request->transaksi_status == "Dikembalikan") {
            //get data item berdasarkan id item
            $item = Item::find($request->transaksi_item);
            //hitung item totalnya dengan barang yang dikembalikan
            $itemTotal = $item->item_jumlah + $request->transaksi_jumlah;
            //perbaharui jumlah data item
            $item->item_jumlah = $itemTotal;
            // return $item;
            $item->save();
        }

        $transaction = Transaction::findOrFail($id);

        $current_date = new DateTime();
        $start_pinjam = new DateTime("$transaction->transaksi_tgl_pinjam");
        $lama_hari_pinjam = $transaction->transaksi_lama_pinjam;

        $interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod(
            $start_pinjam,
            $interval,
            $current_date,
        );

        $listDay = [];
        foreach ($period as $date) {
            array_push($listDay, $date->format('Y-m-d'));
        }

        $jml_hari = count($listDay);
        $status_transaksi = $request->transaksi_status;
        // if ($jml_hari > $lama_hari_pinjam) {
        if ($status_transaksi == 'Terlambat') {
            // $status_transaksi = 'Terlambat';
            $hari_lebih = $jml_hari - $lama_hari_pinjam;
            $denda = $hari_lebih * 10000;
        } else {
            // $status_transaksi = $request->transaksi_status;
            $denda = $request->transaksi_denda;
        }

        $transaction->update([
            'transaksi_item' => $request->transaksi_item,
            'transaksi_peminjam' => $request->transaksi_peminjam,
            'transaksi_jumlah' => $request->transaksi_jumlah,
            'transaksi_tgl_pinjam' => $request->transaksi_tgl_pinjam,
            'transaksi_lama_pinjam' => $request->transaksi_lama_pinjam,
            'transaksi_tgl_kembali' => $request->transaksi_tgl_kembali,
            // 'transaksi_denda' => $request->transaksi_denda,
            // 'transaksi_status' => $request->transaksi_status,
            'transaksi_denda' => $denda,
            'transaksi_status' => $status_transaksi,
            'created_by' => $request->created_by,
            'updated_by' => $request->updated_by,
        ]);

        $data = Transaction::where('transaksi_id', '=', $transaction->transaksi_id)->get();

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
        $transaction = Transaction::findOrFail($id);

        $data = $transaction->delete();

        if ($data) {
            return ApiFormatter::createApi(200, 'Success', $data);
        } else {
            return ApiFormatter::createApi(400, 'Failed');
        }
    }

    public function export()
    {
        return Excel::download(new TransactionsExport, 'transactions.xlsx');
    }
}
