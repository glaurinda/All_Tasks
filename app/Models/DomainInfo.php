<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DomainInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'domain',
        'creation_date',
        'expiration_date',
        'updated_date'
    ];
}
