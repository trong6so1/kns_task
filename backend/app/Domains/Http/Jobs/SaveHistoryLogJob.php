<?php

/**
 * This file is part of RiverCrane's project.
 * (c) 2022 RiverCrane Corp.
 *
 * @copyright 2022 RiverCrane Corp.
 */

namespace App\Domains\Http\Jobs;

use App\Data\Models\ManagementToolHistory;
use App\Data\Repositories\Iam\Traits\CurrentUserTrait;
use Lucid\Units\Job;

class SaveHistoryLogJob extends Job
{
    use CurrentUserTrait;

    private $params;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $params)
    {
        $this->params = $params;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $logModel = isset($this->params['id']) ?
            ManagementToolHistory::find($this->params['id']) :
            new ManagementToolHistory();

        if (isset($this->params['module'])) {
            $logModel->module = $this->params['module'];
        }

        if (isset($this->params['operation'])) {
            $logModel->operation = $this->params['operation'];
        }

        if (isset($this->params['created_by'])) {
            $logModel->created_by = $this->params['created_by'];
        }

        if (isset($this->params['updated_by'])) {
            $logModel->updated_by = $this->params['updated_by'];
        }

        $logModel->save();

        return $logModel;
    }
}
