<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    protected $fillable = [
        'amount', 'isCredit', 'wallet_id'
    ];

    public function wallet()
    {
        return $this->belongsTo('App\Wallet');
    }

//    protected $with = ['wallet'];
}
