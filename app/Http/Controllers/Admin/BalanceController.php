<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Balance;

class BalanceController extends Controller
{
    public function index(){

        $balance = auth()->user()->balance;
        $amout = $balance ? $balance->amout : 0;

        return view('admin.balance.index', compact('amout'));
    }

    public function  deposit(){

        return view('admin.balance.deposit');
    }

    public function depositStore(Request $request){
        
        $balance = auth()->user()->balance()->firstOrCreate([]);
        dd($balance->deposit($request->value));
    }
}
