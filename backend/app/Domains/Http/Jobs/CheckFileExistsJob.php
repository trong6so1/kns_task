<?php

/**
 * This file is part of RiverCrane's project.
 * (c) 2022 RiverCrane Corp.
 *
 * @copyright 2022 RiverCrane Corp.
 */

namespace App\Domains\Http\Jobs;

use Lucid\Units\Job;

class CheckFileExistsJob extends Job
{
    protected $input;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $input)
    {
        $this->input = $input;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $path = $this->input['path'] ?? '';
        $total = $this->input['total'] ?? '';

        if (empty($path) || isEmpty($total)) {
            return false;
        }

        if (file_exists($path)) {
            return [
                'is_exists' => true,
                'progress' => 100,
                'path' => $path
            ];
        } else {
            return $this->checkProgress($path, $total);
        }
    }

    protected function checkProgress($path, $total)
    {
        $compressType = $this->input['compressType'] ?? 'none';

        if ($compressType == 'none') {
            if (!file_exists($path . '.tmp')) {
                return [
                    'is_exists' => false,
                    'progress' => 0,
                    'path' => $path
                ];
            }

            return [
                'is_exists' => false,
                'progress' => (count(file($path . '.tmp')) / $total) * 100,
                'path' => $path
            ];
        } else {
            $folder = str_replace('.' . $compressType, '', $path);

            if (!file_exists($folder)) {
                return [
                    'is_exists' => false,
                    'progress' => 0,
                    'path' => $path
                ];
            }

            $files = array_slice(scandir($folder), 2);
            $totalFiles = count($files);
            $totalRecords = 0;

            foreach ($files as $file) {
                $totalRecords += count(file($folder . '/' . $file));
            }

            $totalRecords -= $totalFiles;

            return [
                'is_exists' => false,
                'progress' => ($totalRecords / $total) * 100,
                'path' => $path
            ];
        }
    }
}
