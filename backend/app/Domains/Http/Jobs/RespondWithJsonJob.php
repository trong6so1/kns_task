<?php

/**
 * This file is part of RiverCrane's project.
 * (c) 2022 RiverCrane Corp.
 *
 * @copyright 2022 RiverCrane Corp.
 */

namespace App\Domains\Http\Jobs;

use Illuminate\Routing\ResponseFactory;
use Lucid\Units\Job;

class RespondWithJsonJob extends Job
{
    protected $status;
    protected $content;
    protected $headers;
    protected $options;

    public function __construct($content = '', $message = '', $status = 200, array $headers = [], $options = 0)
    {
        $this->content = $content;
        $this->message = $message;
        $this->status = $status;
        $this->headers = $headers;
        $this->options = $options;
    }

    public function handle(ResponseFactory $factory)
    {
        $response = [
            'data' => $this->content,
            'code' => $this->status,
        ];

        if ($this->message != '') {
            $response['message'] = $this->message;
        }

        return $factory->json($response, $this->status, $this->headers, $this->options);
    }
}
