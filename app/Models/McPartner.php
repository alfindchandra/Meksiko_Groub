<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class McPartner extends Model
{
    protected $fillable = [
        'name',
        'type',
        'address',
        'contact_person',
        'phone',
    ];
}
