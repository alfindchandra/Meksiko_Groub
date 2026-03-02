<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class McService extends Model
{
    protected $fillable = [
        'name',
        'category',
        'price',
        'description',
    ];
}
