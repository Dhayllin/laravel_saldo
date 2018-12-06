<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Historic extends Model
{
    protected $fillable = [
        'type', 'amout', 'total_before','total_after','user_id_trasaction','date'
    ];
}
