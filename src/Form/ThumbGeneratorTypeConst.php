<?php

declare(strict_types=1);

namespace App\Form;

/**
 * Class ThumbGeneratorTypeConst
 * @package App\Form
 */
class ThumbGeneratorTypeConst
{
    public const FIELD_IMAGE_KEY = 'image';
    public const FIELD_IMAGE_LABEL = 'Obrazek';
    public const FIELD_IMAGE_MAX_SIZE = '10M';
    public const FIELD_IMAGE_MAX_SIZE_MESSAGE = 'Plik jest za duży ({{ size }} {{ suffix }}). Dozwolony maksymalny rozmiar to {{ limit }} {{ suffix }}.';
    public const FIELD_IMAGE_MIME_TYPES = [
        'image/png',
        'image/jpeg',
        'image/jpg',
        'image/gif',
    ];
    public const FIELD_IMAGE_MIME_TYPES_MESSAGE = 'Proszę wybrać plik, który jest obrazkiem (png, jpeg, jpg, gif)';

    public const FIELD_SAVE_LOCATION_KEY = 'save_location';
    public const FIELD_SAVE_LOCATION_LABEL = 'Miejsce zapisu miniaturki';
    public const FIELD_SAVE_LOCATION_SERVER_KEY = 'server';
    public const FIELD_SAVE_LOCATION_SERVER_LABEL = 'Serwer';
    public const FIELD_SAVE_LOCATION_DROPBOX_KEY = 'dropbox';
    public const FIELD_SAVE_LOCATION_DROPBOX_LABEL = 'Dropbox';
    public const FIELD_SAVE_LOCATION_AWS_KEY = 'aws';
    public const FIELD_SAVE_LOCATION_AWS_LABEL = 'Amazon Web Services';
    public const FIELD_SAVE_LOCATIONS = [
        self::FIELD_SAVE_LOCATION_SERVER_KEY => self::FIELD_SAVE_LOCATION_SERVER_LABEL,
        self::FIELD_SAVE_LOCATION_DROPBOX_KEY => self::FIELD_SAVE_LOCATION_DROPBOX_LABEL,
        self::FIELD_SAVE_LOCATION_AWS_KEY => self::FIELD_SAVE_LOCATION_AWS_LABEL,
    ];

    public const FIELD_GENERATE_AND_SEND_KEY = 'generate_and_send';
    public const FIELD_GENERATE_AND_SEND_LABEL = 'Wygeneruj i wyślij miniaturkę';
}
