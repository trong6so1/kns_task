<?php

namespace App\Data\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Supplier extends Model
{
    use HasFactory;
    protected $table = 'sc_shop_Supplier';
    public $keyType = 'string';
    public $incrementing = FALSE;
    protected $fillable = [
        'id',
        'name',
        'alias',
        'email',
        'phone',
        'image',
        'address',
        'url',
        'status',
        'store_id',
        'sort'
    ];
}
