<?php

namespace App\Domains\Brand\Jobs;

use Lucid\Units\Job;
use Illuminate\Support\Str;

class CreateBrandJob extends Job
{
    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $model;
    private $input;
    private $folderPath = '/data/brand';
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
            'id' => Str::random(16),
            'image' =>$this->saveImage($this->input['image']),
            'alias' => $this->input['alias'],
            'name' => $this->input['name'],
            'sort' => $this->input['sort'],
            'url' => $this->input['url'],
            'status' => $this->input['status']
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
