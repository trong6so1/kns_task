<?php

namespace App\Services\Supllier\Features;

use App\Data\Models\Supplier;
use App\Domains\Auth\Jobs\CheckPermissionJob;
use App\Domains\Http\Jobs\BuildListQueryJob;
use App\Domains\Http\Jobs\PaginateJob;
use App\Domains\Http\Jobs\RespondWithJsonErrorJob;
use App\Domains\Http\Jobs\RespondWithJsonJob;
use Illuminate\Http\Request;
use Lucid\Units\Feature;

class ListSupplierFeature extends Feature
{
    public function handle(Request $request)
    {
        $permission = $this->run(CheckPermissionJob::class);

        if (!$permission) {
            return $this->run(RespondWithJsonErrorJob::class, [
                'message' => FORBIDDEN_MSG,
                'status' => FORBIDDEN_STATUS,
            ]);
        }

        $supplier = $this->run(BuildListQueryJob::class, [
            'modelClass' => Supplier::class,
            'select' => [],
        ]);

        $supplier = $this->run(PaginateJob::class, [
            'query' => $supplier,
            'input' => $request->input(),
            'defaultLimit' => PER_PAGE_DEFAULT,
        ]);

        return $this->run(new RespondWithJsonJob($supplier, DONE_MSG));
    }
}
