<?php

namespace App\Services\Category\Operations;

use App\Data\Models\Category;
use Illuminate\Support\Arr;
use Lucid\Units\QueueableOperation;

class UpdateStatusCategoryOperation extends QueueableOperation
{
    /**
     * Create a new operation instance.
     *
     * @return void
     */
    private $input;
    public function __construct(array $input)
    {
        $this->input = $input;
        $this->onQueue('status');
    }

    /**
     * Execute the operation.
     *
     * @return void
     */
    public function handle()
    {
        $data = $this->input['data'];
        $listId = Arr::map($data, function ($item) {
            return $item['id'];
        });
        $listCategory = Category::whereIn('id', $listId);
        $listCategory->chunkByID(1000, function ($categories) {
            foreach ($categories as $category) {
                $category->status = !$category->status;
                $category->save();
            }
        });
    }
}
