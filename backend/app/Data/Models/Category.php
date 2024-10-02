<?php

namespace App\Data\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;
    protected $keyType = 'string';
    protected $table = 'sc_shop_category';
    public $incrementing = false;

    public function parentDescription(){
        return $this->belongsTo(Category::class, 'parent', 'id');
    }

    public function description(){
        return $this->hasMany(CategoryDescription::class, 'category_id', 'id');
    }

    protected $fillable = [
            'id',
            'image',
            'alias',
            'parent',
            'sort',
            'top',
            'status'
        ];

}
