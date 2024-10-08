<?php

namespace App\Services\Supplier\Features;

use App\Data\Models\Supplier;
use App\Domains\Auth\Jobs\CheckPermissionJob;
use App\Domains\Http\Jobs\FindModelJob;
use App\Domains\Http\Jobs\RespondWithJsonErrorJob;
use App\Domains\Http\Jobs\RespondWithJsonJob;
use App\Domains\Supplier\Jobs\EditSupplierJob;
use App\Domains\Supplier\Jobs\ValidationSupplierJob;
use Illuminate\Http\Request;
use Lucid\Units\Feature;

class EditSupplierFeature extends Feature
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

        $supplier = $this->run(FindModelJob::class,[
            'modelClass' => Supplier::class,
            'id' => $this->id,
        ]);

        if(!$supplier)
        {
            return $this->run(RespondWithJsonErrorJob::class,[
                'message' => "Supplier not found",
                'status' => ERROR_STATUS
            ]);
        }

        $request->request->add(['id' => $this->id]);
        $validation = $this->run(ValidationSupplierJob::class,[
            'input' => $request->input(),
            'action' => 'update'
        ]);

        if($validation->fails())
        {
           return $this->run(RespondWithJsonErrorJob::class, [
            'message' => $validation->errors()->getMessages(),
            'status' => ERROR_STATUS
           ]);
        };

        $result = $this->run(EditSupplierJob::class,[
            'input' => $request->input(),
            'supplier' => $supplier
        ]);

        return $this->run(new RespondWithJsonJob(['id'=> $result['id']], $result['isDirty'] ? 'Update Successfully': 'Update Not Change'));
    }
}
