<?php

namespace App\Domains\Http\Services\Compress;

interface CompressInterface
{
    public function compressFolder(string $source, string $destination, bool $isDeleteSource);
    public function rmdirRecursive($dir);
}
