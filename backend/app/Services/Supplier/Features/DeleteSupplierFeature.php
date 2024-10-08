<?php

namespace App\Services\Supplier\Features;

use App\Data\Models\Supplier;
use App\Domains\Auth\Jobs\CheckPermissionJob;
use App\Domains\Http\Jobs\FindModelJob;
use App\Domains\Http\Jobs\RespondWithJsonErrorJob;
use App\Domains\Http\Jobs\RespondWithJsonJob;
use App\Domains\Supplier\Jobs\DeleteSupplierJob;
use Illuminate\Http\Request;
use Lucid\Units\Feature;

class DeleteSupplierFeature extends Feature
{
    private $id;
    function __construct(string|int $id)
    {
        $this->id = $id;
    }
    public function handle(Request $request)
    {
        $permission = $this->run(CheckPermissionJob::class);

        if(!$permission)
        {
            return $this->run(RespondWithJsonErrorJob::class,[
                'message' => FORBIDDEN_MSG,
                'status' => FORBIDDEN_STATUS
            ]);
        }

        $supplier = $this->run(FindModelJob::class,[
            'modelClass' => Supplier::class,
            'id' => $this->id,
        ]);

        if(!$supplier)
        {
            return $this->run(RespondWithJsonErrorJob::class,[
                'message' => RECORD_NOT_FOUND,
                'status' => NOT_FOUND_STATUS
            ]);
        };
        $result = $this->run(DeleteSupplierJob::class,[
            'supplier' => $supplier
        ]);

        return $this->run(new RespondWithJsonJob($result));
    }
}
