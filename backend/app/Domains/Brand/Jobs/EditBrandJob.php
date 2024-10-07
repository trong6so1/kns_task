<?php

namespace App\Domains\Brand\Jobs;

use App\Data\Models\Brand;
use Lucid\Units\Job;

class EditBrandJob extends Job
{
    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $input;
    private $brand;
    private $isDirty;
    private $folderPath = '/data/brand';

    public function __construct(array $input, Brand $brand)
    {
        $this->input = $input;
        $this->brand = $brand;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $oldImage = $this->brand->image;

        $this->brand->fill([

            ...$this->input,
            'image' =>  $this->saveImage($oldImage, $this->brand->image)
        ]);

        if($this->brand->isDirty())
        {
            $validation = dispatch_now(new ValidationBrandJob($this->brand->toArray()));
            if($validation->fails())
            {
                return [
                    'errors' => $validation->errors()
                ];
            }
            $this->isDirty = true;
            $this->brand->save();
        }

        return [
            'brand' => $this->brand,
            'isDirty' => $this->isDirty,
        ];
    }
    private function saveImage($oldPath, $path)
    {
        if ($oldPath == $path || realpath(public_path($oldPath)) === realpath($path)) {
            return $oldPath;
        }
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
