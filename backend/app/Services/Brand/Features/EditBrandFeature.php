<?php

namespace App\Services\Brand\Features;

use App\Data\Models\Brand;
use App\Domains\Auth\Jobs\CheckPermissionJob;
use App\Domains\Brand\Jobs\EditBrandJob;
use App\Domains\Http\Jobs\FindModelJob;
use App\Domains\Http\Jobs\RespondWithJsonErrorJob;
use App\Domains\Http\Jobs\RespondWithJsonJob;
use Illuminate\Http\Request;
use Lucid\Units\Feature;

class EditBrandFeature extends Feature
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
            $this->run(RespondWithJsonErrorJob::class,[
                'message' => FORBIDDEN_MSG,
                'status' => FORBIDDEN_STATUS
            ]);
        };

        $brand = $this->run(FindModelJob::class,[
            'modelClass' => Brand::class,
            'id' => $this->id,
        ]);

        if(!$brand)
        {
            return $this->run(RespondWithJsonErrorJob::class,[
                'message' => "Brand not found",
                'status' => ERROR_STATUS
            ]);
        }

        $result = $this->run(EditBrandJob::class,[
            'input' => $request->input(),
            'brand' => $brand
        ]);

        if(isset($result['errors']))
        {
            return $this->run(RespondWithJsonErrorJob::class,[
                'message' => $result['errors'],
                'status' => ERROR_STATUS
            ]);
        }
        return $this->run(new RespondWithJsonJob(['id'=> $brand->id], $result['isDirty'] ? 'Update Successfully': 'Update Not Change'));
    }
}
