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
    private $model;
    private $folderPath = '/data/category';
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
            'image' => $this->folderPath.'/'.$this->saveImage($this->input['image']),
            'alias' => $this->input['alias'],
            'parent' => $this->input['parent'],
            'sort' => $this->input['sort'],
            'top' => $this->input['top'],
            'status' => $this->input['status']
        ]);
        $this->saveDescription($this->input, $this->model->id);
        $this->model->save();
        return $this->model;
    }

    private function saveDescription($data, $id)
    {
        $descriptionEn = new CategoryDescription();
        $descriptionEn->category_id = $id;
        $descriptionEn->lang = 'en';
        $descriptionEn->title = $data['title_en'];
        $descriptionEn->keyword = $data['keyword_en'] ?? null;
        $descriptionEn->description = $data['description_en'] ?? null;
        $descriptionEn->save();
        $descriptionVi = new CategoryDescription();
        $descriptionVi->category_id = $id;
        $descriptionVi->lang = 'vi';
        $descriptionVi->title = $data['title_vi'];
        $descriptionVi->keyword = $data['keyword_vi'] ?? null;
        $descriptionVi->description = $data['description_vi'] ?? null;
        $descriptionVi->save();
    }


    private function saveImage($path){
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
        return $fileName;
    }
}
