<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\DocBlock\StandardTagFactory;

/**
 * @property int id
 * @property string name
 * @property string type
 * @property int user_id
 * @property User user
 * @property Record[] records
 */
class Wallet extends Model
{
    protected $fillable = [
        'name', 'type', 'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function records()
    {
        return $this->hasMany(Record::class);
    }
}
