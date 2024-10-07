<?php

namespace App\Domains\Brand\Jobs;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Lucid\Units\Job;

class ValidationBrandJob extends Job
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
            'alias' => ['required',Rule::unique('sc_shop_brand','alias')->ignore($this->input['id'] ?? null)],
            'url' => ['required',Rule::unique('sc_shop_brand','url')->ignore($this->input['id'] ?? null)],
            'name' => ['required',Rule::unique('sc_shop_brand','name')->ignore($this->input['id'] ?? null)],
            'image' => 'required|file_exists'
        ];

        $attributes = [
            'name' => __('brand.name'),
            'alias' => __('brand.alias'),
            'url' => __('brand.url'),
            'image' => __('brand.image'),
        ];
        $validation = Validator::make($this->input, $rules, [] ,$attributes);
        return $validation;
    }
}
