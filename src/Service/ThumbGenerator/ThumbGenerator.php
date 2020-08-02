<?php

declare(strict_types=1);

namespace App\Service\ThumbGenerator;

use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class ThumbGenerator
 * @package App\Service\ThumbGenerator
 */
class ThumbGenerator
{
    private const MAX_SIZE_OF_THE_LONG_SIDE = 150;
    private const PREFIX_FOR_TEMPORARY_THUMB_NAME = 'TMP_THUMB_';

    /**
     * @var UploadedFile
     */
    private $imageFile;

    /**
     * @var false|resource
     */
    private $imageResource;

    /**
     * @var int
     */
    private $originalWidth;

    /**
     * @var int
     */
    private $originalHeight;

    /**
     * ThumbGenerator constructor.
     * @param UploadedFile $imageFile
     * @throws ThumbGeneratorException
     */
    public function __construct(UploadedFile $imageFile)
    {
        $this->setImage($imageFile);
    }

    /**
     * @param UploadedFile $imageFile
     * @return $this
     * @throws ThumbGeneratorException
     */
    public function setImage(UploadedFile $imageFile): self
    {
        $this->imageFile = $imageFile;
        $filename = $imageFile->getPathname();

        if (!file_exists($filename)) {
            throw new ThumbGeneratorException(
                sprintf(
                    'Obrazek %s nie może być odnaleziony, spróbuj inny obrazek.',
                    $filename
                )
            );
        }

        switch ($imageFile->getMimeType()) {
            case 'image/png':
                $this->imageResource = @imagecreatefrompng($filename);
                break;
            case 'image/jpg':
            case 'image/jpeg':
                $this->imageResource = imagecreatefromjpeg($filename);
                break;
            case 'image/gif':
                $this->imageResource = @imagecreatefromgif($filename);
                break;
            default:
                throw new ThumbGeneratorException("Plik nie jest obrazkiem, proszę użyć innego typu pliku", 1);
        }

        $this->originalWidth = imagesx($this->imageResource);
        $this->originalHeight = imagesy($this->imageResource);

        return $this;
    }

    /**
     * @return UploadedFile
     */
    public function create(): UploadedFile
    {
        $thumbResource = $this->createResource();
        $tmpThumbPath = $this->createTemporaryFromResource($thumbResource);

        return new UploadedFile(
            $tmpThumbPath,
            $this->imageFile->getClientOriginalName(),
            $this->imageFile->getMimeType(),
            null,
            true
        );
    }

    /**
     * @return false|resource
     */
    private function createResource()
    {
        if ($this->originalWidth > $this->originalHeight) {
            $thumbWidth = min($this->originalWidth, self::MAX_SIZE_OF_THE_LONG_SIDE);
            $thumbHeight = (int)(($thumbWidth * $this->originalHeight)/$this->originalWidth);
        } else {
            $thumbHeight = min($this->originalHeight, self::MAX_SIZE_OF_THE_LONG_SIDE);
            $thumbWidth = (int)(($thumbHeight * $this->originalWidth)/$this->originalHeight);
        }

        $thumb = imagecreatetruecolor($thumbWidth, $thumbHeight);
        imagecopyresampled(
            $thumb,
            $this->imageResource,
            0,
            0,
            0,
            0,
            $thumbWidth,
            $thumbHeight,
            $this->originalWidth,
            $this->originalHeight
        );

        return $thumb;
    }

    /**
     * @param false|resource $thumbResource
     * @return string
     */
    private function createTemporaryFromResource($thumbResource): string
    {
        $tmpThumbPath = tempnam(sys_get_temp_dir(), self::PREFIX_FOR_TEMPORARY_THUMB_NAME);

        switch ($this->imageFile->getMimeType()) {
            case 'image/png':
                if (imagetypes() & IMG_PNG) {
                    imagepng($thumbResource, $tmpThumbPath, 0);
                }
                break;
            case 'image/jpg':
            case 'image/jpeg':
                if (imagetypes() & IMG_JPG) {
                    imagejpeg($thumbResource, $tmpThumbPath, 100);
                }
                break;
            case 'image/gif':
                if (imagetypes() & IMG_GIF) {
                    imagegif($thumbResource, $tmpThumbPath);
                }
                break;
        }

        imagedestroy($thumbResource);

        return $tmpThumbPath;
    }
}
