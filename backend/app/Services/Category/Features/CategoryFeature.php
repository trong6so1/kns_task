<?php

namespace App\Services\Category\Features;

use App\Data\Models\Category;
use App\Domains\Auth\Jobs\CheckPermissionJob;
use App\Domains\Category\Jobs\FilterCategoryJob;
use App\Domains\Category\Jobs\SortCategoryJob;
use App\Domains\Http\Jobs\BuildListQueryJob;
use App\Domains\Http\Jobs\GetRelateDataJob;
use App\Domains\Http\Jobs\PaginateJob;
use App\Domains\Http\Jobs\RespondWithJsonErrorJob;
use App\Domains\Http\Jobs\RespondWithJsonJob;
use Illuminate\Http\Request;
use Lucid\Units\Feature;

class CategoryFeature extends Feature
{
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

        $category = $this->run(BuildListQueryJob::class,[
            'modelClass' => Category::class,
        ]);
        $category = $this->run(FilterCategoryJob::class,[
            'query' => $category,
            'request' => $request->input()
        ]);
        // dd($request->input('lang'));
        $category = $this->run(GetRelateDataJob::class,[
            'query' => $category,
            'nestedRelations' => [
                'parentDescription.description' => function($query) use ($request){
                    $query->where("lang", $request->input('lang') ?? 'vi');
                },
                'description'=> function($query) use ($request){
                    $query->where("lang", $request->input('lang') ?? 'vi');
                }
            ]
        ]);
        $category = $this->run(SortCategoryJob::class, [
            'query' => $category,
            'input' => $request->input()
        ]);
        $category = $this->run(PaginateJob::class,[
            'query' => $category,
            'input' => $request->input(),
            'defaultLimit' => PER_PAGE_DEFAULT
        ]);
        return $this->run(new RespondWithJsonJob($category, DONE_MSG));
    }
}
