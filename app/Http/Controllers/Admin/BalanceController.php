<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BalanceController extends Controller
{
    public function index(){

        $balance = auth()->user()->balance;
        $amout = $balance ? $balance->amout : 0;

        return view('admin.balance.index', compact('amout'));
    }
}
