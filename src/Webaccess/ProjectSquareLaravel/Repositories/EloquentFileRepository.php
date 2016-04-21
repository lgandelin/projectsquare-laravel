<?php

namespace Webaccess\ProjectSquareLaravel\Repositories;

use Webaccess\ProjectSquareLaravel\Models\File;
use Webaccess\ProjectSquareLaravel\Models\Ticket;
use Webaccess\ProjectSquare\Repositories\FileRepository;

class EloquentFileRepository implements FileRepository
{
    public static function getFile($fileID)
    {
        return File::find($fileID);
    }

    public static function getFilesByTicket($ticketID)
    {
        return Ticket::find($ticketID)->files;
    }

    public static function createFile($name, $path, $thumbnailPath, $mimeType, $size, $ticketID, $projectID)
    {
        $file = new File();
        $file->save();

        return self::updateFile($file->id, $name, $path, $thumbnailPath, $mimeType, $size, $ticketID, $projectID);
    }

    public static function updateFile($fileID, $name, $path, $thumbnailPath, $mimeType, $size, $ticketID, $projectID)
    {
        $file = self::getFile($fileID);
        $file->name = $name;
        $file->path = $path;
        $file->thumbnail_path = $thumbnailPath;
        $file->mime_type = $mimeType;
        $file->size = $size;
        $file->ticket_id = $ticketID;
        $file->project_id = $projectID;
        $file->save();

        return $fileID;
    }

    public static function deleteFile($fileID)
    {
        $file = self::getFile($fileID);
        $file->delete();
    }

    public static function getFilesByProject($projectID)
    {
        return File::where('project_id', '=', $projectID)->get();
    }
}