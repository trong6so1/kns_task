<?php

namespace App\Data\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CategoryDescription extends Model
{
    use HasFactory;
    protected $keyType = 'string';
    protected  $primaryKey = 'category_id';
    protected $table = "sc_shop_category_description";
    public $timestamps = FALSE;
    protected $fillable = [
        'category_id',
        'lang',
        'title',
        'keyword',
        'description'
    ];
    public function getKey()
    {
        return ['category','lang'];
    }
}
