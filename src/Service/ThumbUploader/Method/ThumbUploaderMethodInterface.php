<?php

namespace App\Service\ThumbUploader\Method;

use App\Service\ThumbUploader\ThumbUploaderException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Interface ThumbUploaderMethodInterface
 * @package App\Service\ThumbUploader\Method
 */
interface ThumbUploaderMethodInterface
{
    /**
     * @param UploadedFile $thumb
     * @param string $thumbName
     * @throws ThumbUploaderException
     */
    public function upload(UploadedFile $thumb, string $thumbName): void;
}
