<?php

namespace App\Services\Brand\Http\Controllers;

use App\Services\Brand\Features\GetBrandFeature;
use App\Services\Brand\Features\ListBrandFeature;
use Lucid\Units\Controller;

class BrandController extends Controller
{
    public function index() {
        return $this->serve(ListBrandFeature::class);
    }
    public function getBrand($id){
        return $this->serve(GetBrandFeature::class,[
            'id' => $id
        ]);
    }
}
