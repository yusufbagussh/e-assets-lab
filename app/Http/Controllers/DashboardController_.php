<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Borrower;
use App\Models\User;
use App\Models\Item;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use App\Helpers\ApiFormatter;

class DashboardController extends Controller
{
    public function index()
    {
        $totalItems = DB::table('items')->count();
        $totalUsers = DB::table('users')->count();
        $totalBorrowers = DB::table('users')->count();
        $totalTransactions = DB::table('transactions')->count();

        $data = [
            'totalTransactions' => $totalTransactions,
            'totalBorrowers' => $totalBorrowers,
            'totalUsers' => $totalUsers,
            'totalItems' => $totalItems,
        ];

        return ApiFormatter::createApi(200, 'Success', $data);
    }
}
