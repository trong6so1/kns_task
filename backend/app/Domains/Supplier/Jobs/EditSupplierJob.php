<?php

namespace App\Domains\Supplier\Jobs;

use App\Data\Models\Supplier;
use Lucid\Units\Job;

class EditSupplierJob extends Job
{
    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $input;
    private $supplier;
    private $isDirty = false;
    private $folderPath = '/data/supplier';

    public function __construct(array $input, Supplier $supplier)
    {
        $this->input = $input;
        $this->supplier = $supplier;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $oldImage = $this->supplier->image;

        $this->supplier->fill([
            ...$this->input,
            'image' =>  $this->saveImage($oldImage, $this->supplier->image)
        ]);

        if($this->supplier->isDirty())
        {
            $this->isDirty = true;
            $this->supplier->save();
        }

        return [
            'id' => $this->supplier->id,
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
