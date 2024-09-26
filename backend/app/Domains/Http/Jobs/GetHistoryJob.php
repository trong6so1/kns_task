<?php

/**
 * This file is part of RiverCrane's project.
 * (c) 2022 RiverCrane Corp.
 *
 * @copyright 2022 RiverCrane Corp.
 */

namespace App\Domains\Http\Jobs;

use App\Data\Models\ManagementToolHistory;
use App\Data\Models\ProcessReleaseJobQueue;
use Lucid\Units\Job;

class GetHistoryJob extends Job
{
    protected $module;
    protected $relatedModule;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $module, string $relatedModule = '')
    {
        $this->module = $module;
        $this->relatedModule = $relatedModule;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $nestedRelations = ['createdBy:id,name'];
        $nestedRelations['processImportJobQueue.importHistory'] = function ($query) {
            $query->where('status', 9)
                ->select('id', 'process_import_job_queue_id', 'identification_field', 'status', 'message');
        };

        if ($this->module == 'Key Management') {

            $sql = "id, REPLACE('{$this->relatedModule}',
            '{$this->relatedModule}', '{$this->module}') as module, operation, created_by, created_at";

            $history = ManagementToolHistory::where('module', $this->module)
                ->orWhere('module', $this->relatedModule)
                ->whereNotIn('id', function ($query) {
                    $query->selectRaw('management_tool_history_id')->from((new ProcessReleaseJobQueue())->getTable());
                })->selectRaw($sql)
                ->with($nestedRelations);
        } else {
            $history = ManagementToolHistory::where('module', $this->module)
                ->with($nestedRelations);
        }

        return $history;
    }
}
