<?php

namespace App\Domains\Category\Jobs;

use App\Data\Models\Category;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
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
        if(!empty($this->input['image']))
        {
            $this->category->image = $this->saveImage($this->category->image,$this->input['image']);
            unset($this->input['image']);
        }
        $this->category->fill(
            $this->input
        );
        if ($this->category->isDirty()) {

            $this->isDirty = true;
            $this->category->save();
        }
        $this->saveDescription($this->category);
        return [
            'category' => $this->category,
            'isDirty' => $this->isDirty,
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

    public function saveDescription($category)
    {
        $descriptions = $category->description;
        foreach ($descriptions as $description) {
            $lang = $description->lang;
            $description->fill([
                'title' => $this->input['title_' . $lang] ?? $description->title,
                'keyword' => $this->input['keyword_' . $lang] ?? $description->keyword,
                'description' => $this->input['description_' . $lang] ?? $description->description,
            ]);
            if ($description->isDirty()) {
                DB::table('sc_Shop_category_description')
                    ->where('lang', $description->lang)
                    ->where('category_id', $description->category_id)
                    ->update([
                        'title' => $description->title,
                        'keyword' => $description->keyword,
                        'description' => $description->desciption,
                    ]);
                $this->isDirty = true;
            }
        }
    }
}
