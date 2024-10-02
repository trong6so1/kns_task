<?php

namespace App\Services\Category\Features;

use App\Data\Models\Category;
use App\Domains\Auth\Jobs\CheckPermissionJob;
use App\Domains\Category\Jobs\CreateCategoryJob;
use App\Domains\Category\Jobs\ValidationCategoryJob;
use App\Domains\Http\Jobs\NewModelClassJob;
use App\Domains\Http\Jobs\RespondWithJsonErrorJob;
use App\Domains\Http\Jobs\RespondWithJsonJob;
use Illuminate\Http\Request;
use Lucid\Units\Feature;

class CreateCategoryFeature extends Feature
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

        $validation = $this->run(ValidationCategoryJob::class,[
            'input' => $request->input()
        ]);
        if($validation->fails())
        {
           return $this->run(RespondWithJsonErrorJob::class, [
            'message' => $validation->errors()->getMessages(),
            'status' => ERROR_STATUS
           ]);
        };
        $result = $this->run(CreateCategoryJob::class,[
            'input' => $request->input()
        ]);
        return $this->run(new RespondWithJsonJob($result, DONE_MSG));
    }
}
