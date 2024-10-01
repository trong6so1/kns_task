<?php

namespace App\Domains\Category\Jobs;

use App\Data\Models\Category;
use App\Data\Models\CategoryDescription;
use Lucid\Units\Job;

class SortCategoryJob extends Job
{
    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $query;
    private $input;
    public function __construct($query, array $input)
    {
        $this->query = $query;
        $this->input = $input;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $sort = $this->input['sort'] ?? '';
        $category = (new Category())->getTable() . '.id';
        $categoryDescription = (new CategoryDescription())->getTable() . '.category_id';
        if(!empty($sort))
        {
            $sortArr = explode('|' , $sort);
            switch($sortArr[0]){
                case 'id':
                    $this->query = $this->query->orderBy($sortArr[0], $sortArr[1]);
                    break;
                case 'title':
                    $this->query = $this->query->orderBy(
                        CategoryDescription::select('title')->whereColumn($category, $categoryDescription)->where('lang', $this->input['lang'] ?? 'vi'),$sortArr[1]
                    );
                    break;
            }
        }
        else{
            $this->query = $this->query->orderBy('id', 'desc');
        }
        return $this->query;
    }
}
