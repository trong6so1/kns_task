<?php

namespace App\Services\Category\Features;

use App\Domains\Http\Jobs\RespondWithJsonJob;
use App\Services\Category\Operations\UpdateStatusCategoryOperation;
use Illuminate\Http\Request;
use Lucid\Units\Feature;

class UpdateStatusCategoryFeature extends Feature
{
    public function handle(Request $request)
    {
        $this->run(UpdateStatusCategoryOperation::class,[
            'input' => $request->input()
        ]);

        return $this->run(new RespondWithJsonJob('Update Status Has Been Running'));
    }
}
