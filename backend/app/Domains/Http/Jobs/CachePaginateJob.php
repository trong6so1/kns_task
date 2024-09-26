<?php

/**
 * This file is part of RiverCrane's project.
 * (c) 2022 RiverCrane Corp.
 *
 * @copyright 2022 RiverCrane Corp.
 */

namespace App\Domains\Http\Jobs;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Lucid\Units\Job;
use DB;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;

class CachePaginateJob extends Job
{
    protected $query;
    protected $input;
    protected $defaultLimit;
    protected $cacheName;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Builder $query, array $input, int $defaultLimit, string $cacheName)
    {
        $this->query = $query;
        $this->input = $input;
        $this->defaultLimit = $defaultLimit;
        $this->cacheName = $cacheName;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $input = json_encode(Arr::except($this->input, ['page', 'per_page']));
        $cacheName = 'tm:' . $this->cacheName . ':' . $input;

        if (Cache::has($cacheName)) {
            $total = Cache::get($cacheName);
        } else {
            $cloneQuery = clone($this->query);
            $cloneQuery = $cloneQuery->reorder()->select(DB::raw('SQL_CACHE count(1) as aggregate'));
            $total = DB::select($cloneQuery->toSql(), $cloneQuery->getBindings())[0]->aggregate;
            Cache::put($cacheName, $total, 300);
        }

        $data = $this->query->simplePaginate($this->input['per_page'] ?? $this->defaultLimit)->toArray();

        return (new LengthAwarePaginator(
            $data['data'],
            $total,
            $this->input['per_page'] ?? $this->defaultLimit,
            Paginator::resolveCurrentPage(),
            ['path' => Paginator::resolveCurrentPath()]
        ))->toArray();
    }
}
