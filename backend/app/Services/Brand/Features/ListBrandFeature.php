<?php

namespace App\Services\Brand\Features;

use App\Data\Models\Brand;
use App\Domains\Auth\Jobs\CheckPermissionJob;
use App\Domains\Http\Jobs\BuildListQueryJob;
use App\Domains\Http\Jobs\PaginateJob;
use App\Domains\Http\Jobs\RespondWithJsonErrorJob;
use App\Domains\Http\Jobs\RespondWithJsonJob;
use Illuminate\Http\Request;
use Lucid\Units\Feature;

class ListBrandFeature extends Feature
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

        $brand = $this->run(BuildListQueryJob::class, [
            'modelClass' => Brand::class,
            'select' => [],
        ]);
        $brand = $this->run(PaginateJob::class, [
            'query' => $brand,
            'input' => $request->input(),
            'defaultLimit' => PER_PAGE_DEFAULT,
        ]);
        return $this->run(new RespondWithJsonJob($brand, DONE_MSG));
    }
}
