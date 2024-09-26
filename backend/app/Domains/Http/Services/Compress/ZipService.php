<?php

namespace App\Domains\Http\Services\Compress;

use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use InvalidArgumentException;
use ZipArchive;

class ZipService extends CompressAbstract
{
    public function compressFolder(string $source, string $destination, bool $isDeleteSource = true)
    {
        $zip = new ZipArchive();

        if ($zip->open($destination, ZipArchive::CREATE) !== true) {
            throw new InvalidArgumentException('Cannot open ' . $destination);
        }

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(
                $source,
                FilesystemIterator::FOLLOW_SYMLINKS
            ),
            RecursiveIteratorIterator::SELF_FIRST
        );

        while ($iterator->valid()) {
            if (!$iterator->isDot()) {
                $filePath = $iterator->getPathName();
                $relativePath = substr($filePath, strlen($source) + 1);

                if (!$iterator->isDir()) {
                    $zip->addFile($filePath, $relativePath);
                } else {
                    if ($relativePath !== false) {
                        $zip->addEmptyDir($relativePath);
                    }
                }
            }
            $iterator->next();
        }

        $zip->close();

        chmod($destination, 0777);

        if ($isDeleteSource) {
            $this->rmdirRecursive($source);
        }
    }
}
