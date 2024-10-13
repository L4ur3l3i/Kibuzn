<?php

namespace Kibuzn\Services;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Uid\Uuid;

class MediaServices
{
    public const USER_DIRECTORY = 'uploads/users/';
    public const MISC_DIRECTORY = 'uploads/misc/';

    /**
     * Uploads media to the server
     *
     * @param string $type
     * @param UploadedFile $media
     * @param int|Uuid|null $ownerId
     * @return string|null
     */
    public static function uploadMedia(string $type, $media, $ownerId): ?string
    {
        if ($media) {
            switch ($type) {
                case 'avatar':
                    $path = self::USER_DIRECTORY . $ownerId . '/';
                    break;
                default:
                    $path = self::MISC_DIRECTORY;
                    break;
            }

            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            $filename = uniqid() . '.' . $media->guessExtension();
            $media->move($path, $filename);

            // Return the relative path to store in the database (optional)
            return '/' . $path . $filename;
        }

        return null;
    }
}
