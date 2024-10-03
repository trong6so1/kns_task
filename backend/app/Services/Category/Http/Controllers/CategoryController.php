<?php

namespace App\Services\Category\Http\Controllers;

use App\Services\Category\Features\CreateCategoryFeature;
use App\Services\Category\Features\EditCategoryFeature;
use App\Services\Category\Features\GetAllCategoryNameFeature;
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

    public function getAllCategoryName(){
        return $this->serve(GetAllCategoryNameFeature::class);
    }

    public function create(){
        return $this->serve(CreateCategoryFeature::class);
    }

    public function editCategory($id){
        return $this->serve(EditCategoryFeature::class,[
            'id' => $id
        ]);
    }
}
