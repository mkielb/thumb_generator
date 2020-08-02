<?php

declare(strict_types=1);

namespace App\Entity;

use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class ThumbGenerator
 * @package App\Entity
 */
class ThumbGenerator
{
    /**
     * @var UploadedFile
     */
    private $image;

    /**
     * @var string
     */
    private $saveLocation;

    /**
     * @return UploadedFile|null
     */
    public function getImage(): ?UploadedFile
    {
        return $this->image;
    }

    /**
     * @param UploadedFile $image
     * @return $this
     */
    public function setImage(UploadedFile $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSaveLocation(): ?string
    {
        return $this->saveLocation;
    }

    /**
     * @param string $saveLocation
     * @return $this
     */
    public function setSaveLocation(string $saveLocation): self
    {
        $this->saveLocation = $saveLocation;

        return $this;
    }
}
