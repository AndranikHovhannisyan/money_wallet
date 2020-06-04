<?php

namespace App\Http\Controllers;

use App\Http\Requests\WalletRequest;
use App\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WalletController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $wallets = DB::table('wallets', 'w')
            ->select('w.id', 'w.name', 'w.type', DB::raw('SUM(r.amount * (CASE WHEN r.isCredit THEN 1 ELSE -1 END)) as amount'))
            ->leftJoin('records as r', 'w.id', '=', 'r.wallet_id')
            ->where('w.user_id', '=', auth()->user()->id)
            ->groupBy('w.id', 'w.name', 'w.type')
            ->get();

        return view('wallet.index', ['wallets' => $wallets]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('wallet.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  WalletRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(WalletRequest $request)
    {
        $wallet = new Wallet($request->all());
        $wallet->user()->associate(auth()->user());
        $wallet->save();
        return redirect()->route('wallet.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Wallet $wallet
     * @return \Illuminate\Http\Response
     */
    public function edit(Wallet $wallet)
    {
        return view('wallet.form', compact('wallet'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param WalletRequest $request
     * @param Wallet $wallet
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(WalletRequest $request, Wallet $wallet)
    {
        $wallet->update($request->all());

        return redirect()->route('wallet.index');
    }


    /**
     * @param Wallet $wallet
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Wallet $wallet)
    {
        $wallet->delete();

        return redirect()->route('wallet.index');
    }
}
