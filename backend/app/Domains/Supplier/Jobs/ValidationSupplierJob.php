<?php

namespace App\Domains\Supplier\Jobs;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Lucid\Units\Job;

class ValidationSupplierJob extends Job
{
    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $action;
    private $input;
    public function __construct(array $input, string $action)
    {
        $this->input = $input;
        $this->action = $action;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $rules = [
            'name' => ['max:255', Rule::unique('sc_shop_supplier','name')->ignore($this->input['id'] ?? null)],
            'alias' => ['max:120', Rule::unique('sc_shop_supplier','alias')->ignore($this->input['id'] ?? null)],
            'email' => ['max:150', 'email', Rule::unique('sc_shop_supplier','email')->ignore($this->input['id'] ?? null)],
            'url' => ['max:100', Rule::unique('sc_shop_supplier','url')->ignore($this->input['id'] ?? null)],
            'phone' => ['max:20','phone_number'],
            'address' => ['max:100'],
            'status' => ['integer','max:4'],
            'store_id' => ['max:36'],
            'sort' => ['integer','max:11'],
            'image' => ['file_exists']
        ];

        if($this->action == 'create')
        {
            $rulesRequired = ['name', 'alias', 'image'];
            foreach($rulesRequired as $rule)
            {
                $rules[$rule] = [...$rules[$rule], 'required'];
            }
        }

        $attributes = [
            'name' => __('supplier.name'),
            'alias' => __('supplier.alias'),
            'email' => __('supplier.email'),
            'phone' => __('supplier.phone'),
            'image' => __('supplier.image'),
            'address' => __('supplier.address'),
            'url' => __('supplier.url'),
            'status' => __('supplier.status'),
            'store_id' => __('supplier.store_id'),
            'sort' => __('supplier.sort'),
        ];
        $validation = Validator::make($this->input, $rules, [], $attributes);
        return $validation;
    }
}
