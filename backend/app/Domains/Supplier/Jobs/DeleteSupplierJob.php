<?php

namespace App\Domains\Supplier\Jobs;

use App\Data\Models\Supplier;
use Lucid\Units\Job;

class DeleteSupplierJob extends Job
{
    /**
     * Create a new job instance.
     *
     * @return void
     */

    private $supplier;
    public function __construct(Supplier $supplier)
    {
        $this->supplier = $supplier;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->supplier->status = 0;
        $this->supplier->store_id = 0;
        $this->supplier->save();

        return "Delete Supplier Successfully";
    }
}
