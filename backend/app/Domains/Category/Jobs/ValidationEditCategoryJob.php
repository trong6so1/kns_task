<?php

namespace App\Domains\Category\Jobs;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Lucid\Units\Job;

class ValidationEditCategoryJob extends Job
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
            'title_en' => 'min:1|max:200 ',
            'title_vi' => 'min:1|max:200 ',
            'keyword_en' => 'max:200',
            'keyword_vi' => 'max:200',
            'description_en' => 'max:300',
            'description_vi' => 'max:300',
            'parent' => 'parent',
            'alias' => ['min:1',Rule::unique('sc_shop_category','alias')->ignore($this->input['id'] ?? null)],
            'image' => 'file_exists',
        ];
        $attributes = [
            'title_en' => __('category.title_en'),
            'title_vi' =>  __('category.title_vi'),
            'keyword_en' =>  __('category.keyword_en'),
            'keyword_vi' =>  __('category.keyword_vi'),
            'description_en' =>  __('category.description_en'),
            'description_vi' =>  __('category.description_vi'),
            'parent' =>  __('category.parent'),
            'alias' =>  __('category.alias'),
            'image' =>  __('category.image'),
            'sort' =>  __('category.sort')
        ];
        $validation = Validator::make($this->input, $rules, [] , $attributes);
        return $validation;
    }
}
