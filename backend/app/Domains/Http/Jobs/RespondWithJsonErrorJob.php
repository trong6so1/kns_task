<?php

/**
 * This file is part of RiverCrane's project.
 * (c) 2022 RiverCrane Corp.
 *
 * @copyright 2022 RiverCrane Corp.
 */

namespace App\Domains\Http\Jobs;

use Illuminate\Routing\ResponseFactory;

class RespondWithJsonErrorJob extends RespondWithJsonJob
{
    public function __construct($error = '', $message = 'An error occurred', $status = 500, $headers = [], $options = 0)
    {
        $this->content = ['code' => $status];

        if ($error != '') {
            $this->content['error'] = $error;
        }

        if ($message != '') {
            $this->content['message'] = $message;
        }

        $this->status = $status;
        $this->headers = $headers;
        $this->options = $options;
    }

    public function handle(ResponseFactory $response)
    {
        return $response->json($this->content, $this->status, $this->headers, $this->options);
    }
}
