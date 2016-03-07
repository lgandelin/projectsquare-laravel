<?php

namespace Webaccess\ProjectSquareLaravel\Services;

use Webaccess\ProjectSquareLaravel\Repositories\EloquentFileRepository;

class FileManager
{
    public static function getFile($fileID)
    {
        if (!$file = EloquentFileRepository::getFile($fileID)) {
            throw new \Exception(trans('projectsquare::files.file_not_found'));
        }

        return $file;
    }

    public static function getFilesByTicket($ticketID)
    {
        return EloquentFileRepository::getFilesByTicket($ticketID);
    }

    public static function createFile($name, $path, $thumbnailPath, $mimeType, $size, $ticketID)
    {
        return EloquentFileRepository::createFile($name, $path, $thumbnailPath, $mimeType, $size, $ticketID);
    }

    public static function updateFile($fileID, $name, $path, $thumbnailPath, $mimeType, $size, $ticketID)
    {
        return EloquentFileRepository::updateFile($fileID, $name, $path, $thumbnailPath, $mimeType, $size, $ticketID);
    }

    public static function deleteFile($fileID)
    {
        if ($file = self::getFile($fileID)) {
            if (file_exists(public_path('uploads'.$file->thumbnail_path))) {
                unlink(public_path('uploads'.$file->thumbnail_path));
            }

            if (file_exists(public_path('uploads'.$file->path))) {
                unlink(public_path('uploads'.$file->path));
            }

            EloquentFileRepository::deleteFile($fileID);
        }
    }
}
