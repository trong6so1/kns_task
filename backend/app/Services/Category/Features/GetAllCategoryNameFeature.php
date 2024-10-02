<?php

namespace App\Services\Category\Features;

use App\Data\Models\Category;
use App\Domains\Http\Jobs\RespondWithJsonJob;
use Illuminate\Http\Request;
use Lucid\Units\Feature;

class GetAllCategoryNameFeature extends Feature
{
    public function handle(Request $request)
    {
        $listAllCategoryName = Category::select('id', 'alias', 'parent')->get()->toArray();
        foreach($listAllCategoryName as $index => $categoryName)
        {
            $listAllCategoryName[$index]['alias'] = str_replace('-', ' ', ucwords($categoryName['alias'], '-'));
        }
        return $this->run(new RespondWithJsonJob($listAllCategoryName, DONE_MSG));
    }
}
