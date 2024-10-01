<?php

namespace App\Services\Category\Features;

use App\Data\Models\Category;
use App\Domains\Auth\Jobs\CheckPermissionJob;
use App\Domains\Http\Jobs\BuildListQueryJob;
use App\Domains\Http\Jobs\GetEditJob;
use App\Domains\Http\Jobs\RespondWithJsonErrorJob;
use App\Domains\Http\Jobs\RespondWithJsonJob;
use Illuminate\Http\Request;
use Lucid\Units\Feature;

class GetCategoryFeature extends Feature
{
    private $id;
    function __construct($id)
    {
        $this->id =$id;
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
        $category = $this->run(GetEditJob::class,[
            'modelClass' => Category::class,
            'id' => $this->id,
            'nestedRelations' => ['parentDescription', 'description']
        ]);
        if(!$category)
        {
            return $this->run(RespondWithJsonErrorJob::class,[
                'message' => RECORD_NOT_FOUND,
                'status' => NOT_FOUND_STATUS
            ]);
        };
        return $this->run(new RespondWithJsonJob($category, DONE_MSG));
    }
}
