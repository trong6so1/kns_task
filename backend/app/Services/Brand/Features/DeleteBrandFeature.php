<?php

namespace App\Services\Brand\Features;

use App\Data\Models\Brand;
use App\Domains\Auth\Jobs\CheckPermissionJob;
use App\Domains\Brand\Jobs\DeleteBrandJob;
use App\Domains\Http\Jobs\FindModelJob;
use App\Domains\Http\Jobs\GetEditJob;
use App\Domains\Http\Jobs\RespondWithJsonErrorJob;
use App\Domains\Http\Jobs\RespondWithJsonJob;
use Illuminate\Http\Request;
use Lucid\Units\Feature;

class DeleteBrandFeature extends Feature
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

        $brand = $this->run(FindModelJob::class,[
            'modelClass' => Brand::class,
            'id' => $this->id,
        ]);

        if(!$brand)
        {
            return $this->run(RespondWithJsonErrorJob::class,[
                'message' => RECORD_NOT_FOUND,
                'status' => NOT_FOUND_STATUS
            ]);
        };
        $result = $this->run(DeleteBrandJob::class,[
            'brand' => $brand
        ]);

        return $this->run(new RespondWithJsonJob($result));
    }
}
