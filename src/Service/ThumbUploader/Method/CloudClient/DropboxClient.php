<?php

declare(strict_types=1);

namespace App\Service\ThumbUploader\Method\CloudClient;

use Spatie\Dropbox\Client;
use App\Service\ThumbUploader\ThumbUploaderException;
use Throwable;

/**
 * Class DropboxClient
 * @package App\Service\ThumbUploader\Method\CloudClient
 */
class DropboxClient extends AbstractCloudClient
{
    /**
     * @throws ThumbUploaderException
     */
    public function connect(): void
    {
        if (!isset($this->client)) {
            try {
                $this->client = new Client($this->params['token']);
            } catch (Throwable $e) {
                throw new ThumbUploaderException($e->getMessage());
            }
        }
    }

    /**
     * @param false|resource $thumbResource
     * @param string $thumbName
     */
    protected function clientUpload($thumbResource, string $thumbName): void
    {
        $this->client->upload($thumbName, $thumbResource);
    }
}
