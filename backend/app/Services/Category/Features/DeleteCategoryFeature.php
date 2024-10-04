<?php

namespace App\Services\Category\Features;

use App\Data\Models\Category;
use App\Domains\Auth\Jobs\CheckPermissionJob;
use App\Domains\Category\Jobs\DeleteCategoryJob;
use App\Domains\Http\Jobs\FileModelJob;
use App\Domains\Http\Jobs\RespondWithJsonErrorJob;
use App\Domains\Http\Jobs\RespondWithJsonJob;
use Illuminate\Http\Request;
use Lucid\Units\Feature;

class DeleteCategoryFeature extends Feature
{
    private $id;
    function __construct($id)
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

        $category = $this->run(FileModelJob::class,[
            'model' => Category::class,
            'id' => $this->id,
            'nestedRelations' => []
        ]);
        if(!$category)
        {
            return $this->run(RespondWithJsonErrorJob::class,[
                'message' => "Category not found",
                'status' => ERROR_STATUS
            ]);
        }
        $result = $this->run(DeleteCategoryJob::class,[
            'category' => $category
        ]);
        return $this->run(new RespondWithJsonJob(['id'=> $result], 'Delete Successfully'));
    }
}
