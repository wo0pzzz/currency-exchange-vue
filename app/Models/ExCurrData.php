<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExCurrData extends Model
{
    protected $fillable = ['from_curr', 'to_curr', 'from_amount', 'to_amount', 'rate', 'date'];
}
