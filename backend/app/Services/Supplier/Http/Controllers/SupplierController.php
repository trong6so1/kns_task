<?php

namespace App\Services\Supplier\Http\Controllers;

use App\Services\Supllier\Features\ListSupplierFeature;
use App\Services\Supplier\Features\CreateSupplierFeature;
use App\Services\Supplier\Features\DeleteSupplierFeature;
use App\Services\Supplier\Features\EditSupplierFeature;
use App\Services\Supplier\Features\GetSupplierFeature;
use Lucid\Units\Controller;

class SupplierController extends Controller
{
    public function index(){
        return $this->serve(ListSupplierFeature::class);
    }

    public function deleteSupplier(string|int $id){
        return $this->serve(DeleteSupplierFeature::class, [
            'id' => $id
        ]);
    }

    public function getSupplier(string|int $id){
        return $this->serve(GetSupplierFeature::class, [
            'id' => $id
        ]);
    }

    public function createSupplier(){
        return $this->serve(CreateSupplierFeature::class);
    }

    public function EditSupplier($id){
        return $this->serve(EditSupplierFeature::class, [
            'id' => $id
        ]);
    }
}
