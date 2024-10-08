<?php

namespace App\Services\Supplier\Features;

use App\Data\Models\Supplier;
use App\Domains\Auth\Jobs\CheckPermissionJob;
use App\Domains\Http\Jobs\GetNewModelClassJob;
use App\Domains\Http\Jobs\RespondWithJsonErrorJob;
use App\Domains\Http\Jobs\RespondWithJsonJob;
use App\Domains\Supplier\Jobs\CreateSupplierJob;
use App\Domains\Supplier\Jobs\ValidaitonSupplierJob;
use App\Domains\Supplier\Jobs\ValidationSupplierJob;
use Illuminate\Http\Request;
use Lucid\Units\Feature;

class CreateSupplierFeature extends Feature
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

        $validation = $this->run(ValidationSupplierJob::class,[
            'input' => $request->input(),
            'action' => 'create'
        ]);
        if($validation->fails())
        {
           return $this->run(RespondWithJsonErrorJob::class, [
            'message' => $validation->errors()->getMessages(),
            'status' => ERROR_STATUS
           ]);
        };
        $model = $this->run(GetNewModelClassJob::class,[
            'model' => Supplier::class
        ]);
        $result = $this->run(CreateSupplierJob::class,[
            'input' => $request->input(),
            'model' => $model
        ]);
        return $this->run(new RespondWithJsonJob($result, DONE_MSG));
    }
}
