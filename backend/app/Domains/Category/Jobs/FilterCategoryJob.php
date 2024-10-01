<?php

namespace App\Domains\Category\Jobs;

use App\Data\Models\Category;
use Lucid\Units\Job;

class FilterCategoryJob extends Job
{
    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $query;
    private $request;
    public function __construct($query, array $request)
    {
        $this->query = $query;
        $this->request = $request;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if(isset($this->request['keyword'])){

            $this->query = $this->query->whereHas('description', function($query){
                $query->where('title', 'LIKE', '%'.$this->request['keyword'].'%');
            });
        }

        return $this->query;
    }
}
