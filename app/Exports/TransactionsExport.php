<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TransactionsExport implements
    ShouldAutoSize, //Untuk melakukan auto rezise pada lebar kolom
    WithHeadings, //Untuk menambahkan headings
    FromCollection,
    WithTitle,
    WithMapping,
    WithEvents //Untuk memberikan style pada bagian(event) tertentu ke dalam excel
{
    use Exportable;
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // return Transaction::all();
        // return Transaction::with(['item', 'borrower', 'userCreated', 'userUpdated'])
        return Transaction::select('*')
            ->join('items', 'items.item_id', '=', 'transactions.transaksi_item')
            ->join('borrowers', 'borrowers.peminjam_id', '=', 'transactions.transaksi_peminjam')
            ->join('users', 'users.id', '=', 'transactions.created_by')
            // ->join('users', 'users.id', '=', 'transactions.updated_by')
            ->get();
    }

    public function map($transaction): array
    {
        return [
            $transaction->transaksi_id,
            $transaction->item->item_nama,
            $transaction->item->item_id,
            $transaction->borrower->peminjam_nama,
            $transaction->borrower->peminjam_no_identitas,
            $transaction->transaksi_jumlah,
            $transaction->transaksi_tgl_pinjam,
            $transaction->transaksi_lama_pinjam,
            $transaction->transaksi_tgl_kembali,
            $transaction->transaksi_denda,
            $transaction->transaksi_status,
            $transaction->created_at,
            $transaction->user_nama,
            $transaction->updated_at,
            $transaction->user_nama,
        ];
    }

    public function headings(): array
    {
        return [
            'TRANSAKSI ID',
            'ITEM',
            'ID ITEM',
            'PEMINJAM',
            'ID PEMINJAMAN',
            'JUMLAH',
            'TGL PEMINJAMAN',
            'LAMA PEMINJAMAN',
            'TGL PENGEMBALIAN',
            'DENDA',
            'STATUS',
            'CREATED AT',
            'CREATED BY',
            'UPDATED AT',
            'UPDATED BY',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getStyle('A1:M1')->applyFromArray([
                    'font' => [
                        'bold' => true
                    ]
                ]);
            }
        ];
    }

    public function title(): string
    {
        return 'Data Transaksi';
    }
}
