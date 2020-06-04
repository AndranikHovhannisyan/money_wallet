<?php

namespace App\Http\Controllers;

use App\Http\Requests\RecordRequest;
use App\Record;
use App\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RecordController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @param Wallet $wallet
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Wallet $wallet)
    {
        $records = Record::where('wallet_id', $wallet->id)->get();
        return view('record.index', compact('wallet','records'));
    }

    /**
     * @param RecordRequest $request
     * @param Wallet $wallet
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(RecordRequest $request, Wallet $wallet)
    {
        $record = new Record($request->all());
        $record->wallet()->associate($wallet);
        $record->save();

        return back();
    }
}
