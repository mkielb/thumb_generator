<?php

declare(strict_types=1);

namespace App\Service\ThumbUploader\Method\CloudClient;

use App\Service\ThumbUploader\ThumbUploaderException;
use App\Service\ThumbUploader\Method\ThumbUploaderMethodInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Throwable;

/**
 * Class AbstractCloudClient
 * @package App\Service\ThumbUploader\Method\CloudClient
 */
abstract class AbstractCloudClient implements ThumbUploaderMethodInterface
{
    /**
     * @var array
     */
    protected $params;

    protected $client;

    /**
     * AbstractCloudClient constructor.
     * @param array $params
     */
    public function __construct(array $params = []) {
        $this->params = $params;
    }

    /**
     * @throws ThumbUploaderException
     */
    public abstract function connect(): void;

    /**
     * @param UploadedFile $thumb
     * @param string $thumbName
     * @throws ThumbUploaderException
     */
    public function upload(UploadedFile $thumb, string $thumbName): void
    {
        try {
            $this->connect();
            $this->clientUpload(fopen($thumb->getPathname(), 'r'), $thumbName);
        } catch (Throwable $e) {
            throw new ThumbUploaderException($e->getMessage());
        }
    }

    /**
     * @param false|resource $thumbResource
     * @param string $thumbName
     */
    protected abstract function clientUpload($thumbResource, string $thumbName): void;
}
