<?php

namespace App\Services\Category\Http\Controllers;

use App\Services\Category\Features\CategoryFeature;
use Lucid\Units\Controller;

class CategoryController extends Controller
{
    public function index(){
        return $this->serve(CategoryFeature::class,[]);
    }
}
