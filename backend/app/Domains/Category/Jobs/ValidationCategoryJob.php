<?php

namespace App\Domains\Category\Jobs;


// use Illuminate\Support\Facades\Validator;
use Validator;
use Lucid\Units\Job;

class ValidationCategoryJob extends Job
{
    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $input;
    public function __construct(array $input)
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
        $rules = [
            'name_en' => 'required|max:200|unique_title',
            'name_vi' => 'required|max:200|unique_title',
            'keyword_en' => 'max:200',
            'keyword_vi' => 'max:200',
            'description_en' => 'max:300',
            'description_vi' => 'max:300',
            'parent' => 'required|parent',
            'url' => 'required|unique:sc_shop_category,alias,id',
            'image' => 'required|file_exists',
            'sort' => 'required'
        ];
        $attributes = [
            'name_en' => __('category.name_en'),
            'name_vi' =>  __('category.name_vi'),
            'keyword_en' =>  __('category.keyword_en'),
            'keyword_vi' =>  __('category.keyword_vi'),
            'description_en' =>  __('category.description_en'),
            'description_vi' =>  __('category.description_vi'),
            'parent' =>  __('category.parent'),
            'url' =>  __('category.url'),
            'image' =>  __('category.image'),
            'sort' =>  __('category.sort')
        ];
        $validation = Validator::make($this->input, $rules, [] , $attributes);
        return $validation;
    }
}
