<?php

namespace App\Services\Category\Http\Controllers;

use App\Services\Category\Features\GetCategoryFeature;
use App\Services\Category\Features\ListCategoryFeature;
use Lucid\Units\Controller;

class CategoryController extends Controller
{
    public function index(){
        return $this->serve(ListCategoryFeature::class,[]);
    }
    public function getCategory($id)
    {
        return $this->serve(GetCategoryFeature::class,[
            'id' => $id
        ]);
    }
}
