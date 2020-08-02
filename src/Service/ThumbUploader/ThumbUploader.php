<?php

declare(strict_types=1);

namespace App\Service\ThumbUploader;

use App\Form\ThumbGeneratorTypeConst;
use App\Service\ThumbUploader\Method\CloudClient\AWSClient;
use App\Service\ThumbUploader\Method\CloudClient\DropboxClient;
use App\Service\ThumbUploader\Method\Server;
use App\Service\ThumbUploader\Method\ThumbUploaderMethodInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Throwable;

/**
 * Class ThumbUploader
 * @package App\Service\ThumbUploader
 */
class ThumbUploader
{
    /**
     * @var SluggerInterface
     */
    private $slugger;

    /**
     * @var Server
     */
    private $server;

    /**
     * @var DropboxClient
     */
    private $dropboxClient;

    /**
     * @var AWSClient
     */
    private $awsClient;

    /**
     * ThumbUploader constructor.
     * @param SluggerInterface $slugger
     * @param Server $server
     * @param DropboxClient $dropboxClient
     * @param AWSClient $awsClient
     */
    public function __construct(
        SluggerInterface $slugger,
        Server $server,
        DropboxClient $dropboxClient,
        AWSClient $awsClient
    ) {
        $this->slugger = $slugger;
        $this->server = $server;
        $this->dropboxClient = $dropboxClient;
        $this->awsClient = $awsClient;
    }

    /**
     * @param string $saveLocation
     * @param UploadedFile $thumb
     * @throws ThumbUploaderException
     */
    public function upload(string $saveLocation, UploadedFile $thumb)
    {
        try {
            $this->getMethodBySaveLocation($saveLocation)
                ->upload($thumb, $this->getThumbName($thumb));
        } catch (Throwable $e) {
            throw new ThumbUploaderException($e->getMessage());
        }
    }

    /**
     * @param string $saveLocation
     * @return ThumbUploaderMethodInterface
     * @throws ThumbUploaderException
     */
    private function getMethodBySaveLocation(string $saveLocation): ThumbUploaderMethodInterface
    {
        switch ($saveLocation) {
            case ThumbGeneratorTypeConst::FIELD_SAVE_LOCATION_SERVER_KEY:
                return $this->server;
            case ThumbGeneratorTypeConst::FIELD_SAVE_LOCATION_DROPBOX_KEY:
                return $this->dropboxClient;
            case ThumbGeneratorTypeConst::FIELD_SAVE_LOCATION_AWS_KEY:
                return $this->awsClient;
            default:
                throw new ThumbUploaderException(
                    sprintf(
                        'Brak obsługi wybranej metody wysyłki miniaturki: %s',
                        $saveLocation
                    )
                );
        }
    }

    /**
     * @param UploadedFile $thumb
     * @return string
     */
    private function getThumbName(UploadedFile $thumb): string
    {
        $originalFilename = pathinfo($thumb->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);

        return $safeFilename . '-' . uniqid() . '.' . $thumb->guessExtension();
    }
}
