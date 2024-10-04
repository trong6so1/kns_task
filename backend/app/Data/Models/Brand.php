<?php

namespace App\Data\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Brand extends Model
{
    use HasFactory;
    protected $table = 'sc_shop_brand';
    protected $keyType = 'string';
    protected $fillable = [
        'id',
        'name',
        'alias',
        'image',
        'url',
        'sort',
        'status'
    ];
}
