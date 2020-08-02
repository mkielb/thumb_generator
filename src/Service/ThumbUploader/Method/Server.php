<?php

declare(strict_types=1);

namespace App\Service\ThumbUploader\Method;

use App\Service\ThumbUploader\ThumbUploaderException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Throwable;

/**
 * Class Server
 * @package App\Service\ThumbUploader\Method
 */
class Server implements ThumbUploaderMethodInterface
{
    /**
     * @var string
     */
    private $targetDirectory;

    /**
     * Server constructor.
     * @param string $targetDirectory
     */
    public function __construct(string $targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    /**
     * @param UploadedFile $thumb
     * @param string $thumbName
     * @throws ThumbUploaderException
     */
    public function upload(UploadedFile $thumb, string $thumbName): void
    {
        try {
            $thumb->move($this->targetDirectory, $thumbName);
        } catch (Throwable $e) {
            throw new ThumbUploaderException($e->getMessage());
        }
    }
}
