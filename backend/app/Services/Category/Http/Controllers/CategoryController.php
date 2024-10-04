<?php

namespace App\Services\Category\Http\Controllers;

use App\Services\Category\Features\CreateCategoryFeature;
use App\Services\Category\Features\DeleteCategoryFeature;
use App\Services\Category\Features\EditCategoryFeature;
use App\Services\Category\Features\GetAllCategoryNameFeature;
use App\Services\Category\Features\GetCategoryFeature;
use App\Services\Category\Features\ListCategoryFeature;
use App\Services\Category\Features\UpdateStatusCategoryFeature;
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

    public function deleteCategory($id){
        return $this->serve(DeleteCategoryFeature::class,[
            'id' => $id
        ]);
    }

    public function updateStatusCategory(){
        return $this->serve(UpdateStatusCategoryFeature::class);
    }
}
