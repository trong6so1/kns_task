<?php

namespace App\Services\Brand\Features;

use App\Data\Models\Brand;
use App\Domains\Auth\Jobs\CheckPermissionJob;
use App\Domains\Brand\Jobs\CreateBrandJob;
use App\Domains\Brand\Jobs\ValidationBrandJob;
use App\Domains\Http\Jobs\GetNewModelClassJob;
use App\Domains\Http\Jobs\RespondWithJsonErrorJob;
use App\Domains\Http\Jobs\RespondWithJsonJob;
use Illuminate\Http\Request;
use Lucid\Units\Feature;

class CreateBrandFeature extends Feature
{
    public function handle(Request $request)
    {
        $permission = $this->run(CheckPermissionJob::class);

        if(!$permission){
            return $this->run(RespondWithJsonErrorJob::class,[
                'message' => FORBIDDEN_MSG,
                'status' => FORBIDDEN_STATUS
            ]);
        };

        $validation = $this->run(ValidationBrandJob::class,[
            'input' => $request->input()
        ]);
        if($validation->fails())
        {
           return $this->run(RespondWithJsonErrorJob::class, [
            'message' => $validation->errors()->getMessages(),
            'status' => ERROR_STATUS
           ]);
        };
        $model = $this->run(GetNewModelClassJob::class,[
            'model' => Brand::class
        ]);
        $result = $this->run(CreateBrandJob::class,[
            'input' => $request->input(),
            'model' => $model
        ]);
        return $this->run(new RespondWithJsonJob($result, DONE_MSG));
    }
}
