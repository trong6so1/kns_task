<?php

namespace App\Domains\Category\Jobs;

use App\Data\Models\Category;
use Illuminate\Support\Arr;
use Lucid\Units\Job;

class EditCategoryJob extends Job
{
    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $category;
    private $input;
    private $isDirty = false;
    private $folderPath = '/data/category';
    public function __construct(Category $category, array $input)
    {
        $this->category = $category;
        $this->input = $input;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->category->fill([
            'image' => $this->saveImage($this->category['image'], $this->input['image']),
            'alias' => $this->input['url'],
            'parent' => $this->input['parent'],
            'sort' => $this->input['sort'],
            'top' => $this->input['top'],
            'status' => $this->input['status'],
        ]);
        if ($this->category->isDirty()) {
            $this->isDirty = true;
            $this->category->save();
        }
        $this->saveDescription($this->category);
        return [
            'category' => $this->category,
            'isDirty' => $this->isDirty
        ];
    }

    private function saveImage($imagePath, $path)
    {
        if ($imagePath == $path || realpath(public_path($imagePath)) === realpath($path)) {
            return $imagePath;
        }
        $fileName = pathinfo($path, PATHINFO_FILENAME);
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        $folderPath = public_path($this->folderPath);
        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0755, true);
        }
        $countFile = count(glob($folderPath . '/' . $fileName . '*' . $extension));
        $fileName = $countFile > 0 ? $fileName . $countFile . $extension : $fileName . '.' . $extension;
        $filePath = $folderPath . '/' . $fileName;
        rename($path, $filePath);
        return $folderPath . '/' . $fileName;
    }

    public function saveDescription($category)
    {
        $descriptions = $category->description;
        foreach($descriptions as $description)
        {
            $lang = $description->lang;
            $description->fill([
                'title' => $this->input['name_'.$lang],
                'keyword' => $this->input['keyword_'.$lang],
                'description' => $this->input['description_'.$lang],
            ]);
            if($description->isDirty())
            {
                $this->isDirty = true;
                $description->save();
            }
        }
    }
}
