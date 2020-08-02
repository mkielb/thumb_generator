<?php

declare(strict_types=1);

namespace App\Service\ThumbUploader\Method\CloudClient;

use App\Service\ThumbUploader\ThumbUploaderException;
use Aws\S3\S3Client as Client;
use Throwable;

/**
 * Class AWSClient
 * @package App\Service\ThumbUploader\Method\CloudClient
 */
class AWSClient extends AbstractCloudClient
{
    /**
     * @throws ThumbUploaderException
     */
    public function connect(): void
    {
        if (!isset($this->client)) {
            try {
                $this->client = new Client([
                    'version' => $this->params['version'],
                    'region' => $this->params['region'],
                    'credentials' => [
                        'key' => $this->params['key'],
                        'secret' => $this->params['secret'],
                    ],
                ]);
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
        $this->client->putObject([
            'Bucket' => $this->params['bucket'],
            'Key' => $thumbName,
            'Body' => $thumbResource,
            'ACL' => $this->params['acl'],
        ]);
    }
}
