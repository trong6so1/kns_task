<?php

namespace App\Domains\Supplier\Jobs;

use Lucid\Units\Job;
use Illuminate\Support\Str;

class CreateSupplierJob extends Job
{
    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $model;
    private $input;
    private $folderPath = '/data/supplier';
    public function __construct(array $input, $model)
    {
        $this->input = $input;
        $this->model = $model;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->model->fill([
            ...$this->input,
            'id' => Str::random(16),
            'image' =>$this->saveImage($this->input['image']),
        ]);
        $this->model->save();
        return $this->model;
    }

    private function saveImage($path)
    {
        $fileName = pathinfo($path, PATHINFO_FILENAME);
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        $folderPath = public_path($this->folderPath);
        if(!file_exists($folderPath))
        {
            mkdir($folderPath, 0755, true);
        }
        $countFile = count(glob($folderPath . '/' . $fileName. '*'. $extension));
        $fileName = $countFile > 0 ? $fileName.'('.$countFile.').'.$extension : $fileName.'.'.$extension;

        $filePath = $folderPath . '/' . $fileName;
        copy($path, $filePath);
        return $this->folderPath . '/' . $fileName;
    }
}
