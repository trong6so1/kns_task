<?php

namespace App\Services\Category\Features;

use App\Domains\Http\Jobs\RespondWithJsonJob;
use Illuminate\Http\Request;
use Lucid\Units\Feature;

class CategoryFeature extends Feature
{
    public function handle(Request $request)
    {
        return $this->run(RespondWithJsonJob::class,[
            'status' => FORBIDDEN_STATUS,
            'message' => FORBIDDEN_MSG
        ]);
    }
}
