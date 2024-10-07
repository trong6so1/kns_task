<?php

namespace App\Domains\Brand\Jobs;

use App\Data\Models\Brand;
use Lucid\Units\Job;

class DeleteBrandJob extends Job
{
    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $brand;

    public function __construct(Brand $brand)
    {
        $this->brand = $brand;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->brand->status = 0;
        $this->brand->save();
        return "Delete Brand Successfully";
    }
}
