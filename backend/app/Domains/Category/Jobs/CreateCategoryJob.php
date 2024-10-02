<?php

namespace App\Domains\Category\Jobs;

use App\Data\Models\Category;
use App\Data\Models\CategoryDescription;
use Lucid\Units\Job;
use Illuminate\Support\Str;

use function PHPUnit\Framework\fileExists;

class CreateCategoryJob extends Job
{
    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $input;
    public function __construct($input)
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
        $category = new Category();
        $category->fill([
            'id' => Str::random(16),
            'image' => $this->saveImage($this->input['image']),
            'alias' => $this->input['url'],
            'parent' => $this->input['parent'],
            'sort' => $this->input['sort'],
            'top' => $this->input['top'],
            'status' => $this->input['status']
        ]);
        $this->saveDescription($this->input, $category->id);
        $category->save();
        return $category;
    }

    private function saveDescription($data, $id)
    {
        $descriptionEn = new CategoryDescription();
        $descriptionEn->category_id = $id;
        $descriptionEn->lang = 'en';
        $descriptionEn->title = $data['name_en'];
        $descriptionEn->keyword = $data['keyword_en'] ?? null;
        $descriptionEn->description = $data['description_en'] ?? null;
        $descriptionEn->save();
        $descriptionVi = new CategoryDescription();
        $descriptionVi->category_id = $id;
        $descriptionVi->lang = 'vi';
        $descriptionVi->title = $data['name_vi'];
        $descriptionVi->keyword = $data['keyword_vi'] ?? null;
        $descriptionVi->description = $data['description_vi'] ?? null;
        $descriptionVi->save();
    }


    private function saveImage($path){
        $fileName = pathinfo($path, PATHINFO_FILENAME);
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        $folderPath = public_path('data/category');
        $countFile = count(glob($folderPath . '/' . $fileName. '*'. $extension));
        $fileName = $countFile > 0 ? $fileName.$countFile : $fileName;
        $filePath = $folderPath . '/' . $fileName . '.' . $extension;
        if(!file_exists($folderPath))
        {
            mkdir($folderPath, 0755, true);
        }
        rename($path, $filePath);
    }
}
