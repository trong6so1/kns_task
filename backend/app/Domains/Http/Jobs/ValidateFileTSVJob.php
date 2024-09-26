<?php

/**
 * This file is part of RiverCrane's project.
 * (c) 2022 RiverCrane Corp.
 *
 * @copyright 2022 RiverCrane Corp.
 */

namespace App\Domains\Http\Jobs;

use Lucid\Units\Job;

class ValidateFileTSVJob extends Job
{
    protected $file;
    protected $modelClass;
    protected $input;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $file, string $modelClass, array $input = [])
    {
        $this->file = $file;
        $this->modelClass = $modelClass;
        $this->input = $input;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $result = [];
        $type = $this->input['type'] ?? 'add_new_key';

        if (isset($this->file['tsv_file'])) {
            $model = new $this->modelClass();
            $file = $this->file['tsv_file'];
            $errMsg = [];
            $ext = $file->getClientOriginalExtension();
            $fileSize = config('upload.tsv.max_size');
            $fileRow = config('upload.tsv.row_limit');

            $errMsg = $this->validateFormat($errMsg, $file);
            $errMsg = $this->validateExtension($errMsg, $ext, 'tsv');
            $errMsg = $this->validateSize($errMsg, $file, $fileSize);
            $errMsg = $this->validateRowLimit($errMsg, $file, $fileRow);

            if (($handle = fopen($file, 'r')) !== false) {
                $row = fgetcsv($handle, 0, "\t");
                $errMsg = $this->validateHeader($errMsg, $row, $model->getHeaderExport($type));

                $row = fgetcsv($handle, 0, "\t");
                $errMsg = $this->validateContent($errMsg, $row);
            }
        } else {
            $errMsg[] = FIFE_NOT_FOUND;
        }

        $result['error'] = $errMsg;

        return $result;
    }

    protected function validateFormat($errMsg, $file)
    {
        $enc = mb_check_encoding(file_get_contents($file), 'UTF-8');

        if (!$enc) {
            $errMsg[] = INVALID_FILE_ENCODE_FORMAT;
        }

        return $errMsg;
    }

    protected function validateExtension($errMsg, $extension, $format)
    {
        if ($extension != $format) {
            $errMsg[] = INVALID_FILE_EXTENSION;
        }

        return $errMsg;
    }

    protected function validateSize($errMsg, $file, $size)
    {
        if (filesize($file) > $size) {
            $errMsg[] = sprintf(INVALID_FILE_SIZE, $this->formatBytes($size));
        }

        return $errMsg;
    }

    protected function validateRowLimit($errMsg, $file, $rowLimit)
    {
        if (count(file($file)) > ($rowLimit + 1)) {
            $errMsg[] = sprintf(INVALID_FILE_ROW, number_format($rowLimit));
        }

        return $errMsg;
    }

    protected function validateHeader($errMsg, $fileHeader, $header)
    {
        if ($fileHeader != $header) {
            $errMsg[] = INVALID_FILE_HEADER;
        }

        return $errMsg;
    }

    protected function validateContent($errMsg, $fileContent)
    {
        if ($fileContent == false) {
            $errMsg[] = FILE_NO_DATA;
        }

        return $errMsg;
    }

    protected function formatBytes($bytes, $precision = 2)
    {
        $unit = ['B', 'KB', 'MB', 'GB'];
        $exp = floor(log($bytes, 1024)) | 0;

        return round($bytes / (pow(1024, $exp)), $precision) . ' ' . $unit[$exp];
    }
}
