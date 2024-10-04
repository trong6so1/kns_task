<?php

namespace App\Domains\Category\Jobs;

use App\Data\Models\Category;
use Lucid\Units\Job;

class DeleteCategoryJob extends Job
{
    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $category;
    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $id = $this->category->id;
        $this->category->top = 0;
        $this->category->save();
        // $this->category->delete();
        return $id;
    }
}
