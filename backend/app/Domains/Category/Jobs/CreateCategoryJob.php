<?php

namespace App\Domains\Category\Jobs;

use App\Data\Models\Category;
use App\Data\Models\CategoryDescription;
use Lucid\Units\Job;
use Illuminate\Support\Str;
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
            'image' => '/data/category/'. $this->input['image'],
            'alias' => $this->input['url'],
            'parent' => $this->input['parent'],
            'sort' => $this->input['sort'],
            'top' => $this->input['top'],
            'status' => $this->input['status']
        ]);
        $this->saveDescription($this->input, $category->id);
        $this->saveImage($this->input['image'], $category->image);
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

    private function saveImage($path, $image){
        file($path)->storeAs($image, 'abc.jpg');
    }
}
