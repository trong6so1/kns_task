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

class RespondWithDeleteFileAfterDownloadJob extends Job
{
    protected $file;
    protected $name;
    protected $headers;
    protected $disposition;
    protected $deleteFileAfterSend;

    public function __construct($file, $name = null, array $headers = [], $disposition = 'attachment', $deleteFileAfterSend = true)
    {
        $this->file = $file;
        $this->name = $name;
        $this->headers = $headers;
        $this->disposition = $disposition;
        $this->deleteFileAfterSend = $deleteFileAfterSend;
    }

    public function handle(ResponseFactory $factory)
    {
        return $factory->download($this->file, $this->name, $this->headers, $this->disposition = 'attachment')
            ->deleteFileAfterSend($this->deleteFileAfterSend);
    }
}
