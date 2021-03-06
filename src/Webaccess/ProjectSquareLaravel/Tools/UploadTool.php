<?php

namespace Webaccess\ProjectSquareLaravel\Tools;

use Intervention\Image\Facades\Image;

class UploadTool
{
    const UPLOADS_FOLDER = 'uploads';
    public static function uploadFileForTicket($files, $ticketID)
    {
        $uploadsFolder = public_path(self::UPLOADS_FOLDER).'/tickets';
        $ticketFolder = '/'.$ticketID.'/';
        $destinationPath = $uploadsFolder.$ticketFolder;

        self::createFolderIfNotExists($uploadsFolder);
        self::createFolderIfNotExists($destinationPath);

        $uploadedFile = null;
        $thumbnailName = null;
        foreach ($files as $file) {
            $mimeType = $file->getMimeType();
            $uploadedFile = $file->move($destinationPath, StringTool::slugify($file->getClientOriginalName()));
            $thumbnailName = self::createThumbnail($destinationPath, $uploadedFile, $mimeType);
        }
        $data = new \StdClass();
        $data->name = $uploadedFile->getFilename();
        $data->size = $uploadedFile->getSize();
        $data->mimeType = $mimeType;
        $data->path = $ticketFolder.$uploadedFile->getFilename();
        $data->thumbnailPath = $ticketFolder.$thumbnailName;
        $data->url = asset('/'.self::UPLOADS_FOLDER.'/tickets'.$data->path);
        $data->thumbnailUrl = asset('/'.self::UPLOADS_FOLDER.'/tickets'.$data->thumbnailPath);
        $data->deleteUrl = null;
        $data->deleteType = null;
        $data->fileID = time();

        return $data;
    }

    public static function uploadFileForProject($files, $projectID)
    {
        $uploadsFolder = public_path(self::UPLOADS_FOLDER).'/projects';
        $projectFolder = '/'.$projectID.'/';
        $destinationPath = $uploadsFolder.$projectFolder;

        self::createFolderIfNotExists($uploadsFolder);
        self::createFolderIfNotExists($destinationPath);

        $uploadedFile = null;
        $thumbnailName = null;
        foreach ($files as $file) {
            $mimeType = $file->getMimeType();
            $uploadedFile = $file->move($destinationPath, StringTool::slugify($file->getClientOriginalName()));
            $thumbnailName = self::createThumbnail($destinationPath, $uploadedFile, $mimeType);
        }
        $data = new \StdClass();
        $data->name = $uploadedFile->getFilename();
        $data->size = $uploadedFile->getSize();
        $data->mimeType = $mimeType;
        $data->path = $projectFolder.$uploadedFile->getFilename();
        $data->thumbnailPath = $projectFolder.$thumbnailName;
        $data->url = asset('/'.self::UPLOADS_FOLDER.'/projects'.$data->path);
        $data->thumbnailUrl = asset('/'.self::UPLOADS_FOLDER.'/projects'.$data->thumbnailPath);
        $data->deleteUrl = null;
        $data->deleteType = null;
        $data->fileID = time();

        return $data;
    }

    public static function uploadFileForUser($files, $userID)
    {
        $uploadsFolder = public_path(self::UPLOADS_FOLDER).'/users';
        $userFolder = '/'.$userID.'/';
        $destinationPath = $uploadsFolder.$userFolder;

        self::createFolderIfNotExists($uploadsFolder);
        self::createFolderIfNotExists($destinationPath);

        $uploadedFile = null;
        $thumbnailName = null;
        foreach ($files as $file) {
            $mimeType = $file->getMimeType();
            $uploadedFile = $file->move($destinationPath, StringTool::slugify($file->getClientOriginalName()));

            Image::make($destinationPath.$uploadedFile->getFilename())
                ->fit(150, 150)
                ->encode('jpg', 85)
                ->save($destinationPath.'avatar.jpg');
        }
        $data = new \StdClass();
        $data->name = $uploadedFile->getFilename();
        $data->size = $uploadedFile->getSize();
        $data->mimeType = $mimeType;
        $data->path = $destinationPath.'avatar.jpg';
        $data->url = asset('/'.self::UPLOADS_FOLDER.'/users'.$data->path);

        return $data;
    }

    private static function createFolderIfNotExists($uploadsFolder)
    {
        if (!is_dir($uploadsFolder)) {
            mkdir($uploadsFolder);
        }
    }

    private static function createThumbnail($destinationPath, $uploadedFile, $mimeType)
    {
        $thumbnailName = null;
        if (preg_match('/image\/jpeg/', $mimeType) || preg_match('/image\/png/', $mimeType) || preg_match('/image\/gif/', $mimeType)) {
            $thumbnailName = 'thumbnail.'.$uploadedFile->getFilename();
            Image::make($destinationPath.$uploadedFile->getFilename())
                ->fit(135, 80)
                ->save($destinationPath.$thumbnailName);
        } else {
            $img = Image::canvas(135, 80, '#fff');
            $img->fill('#ffffff');

            $img->text($mimeType, 70, 40, function ($font) {
                $font->size(150);
                $font->file(2);
                $font->color('#000');
                $font->align('center');
                $font->valign('middle');
            });

            $extension = FileTool::extractExtension($uploadedFile->getFilename());
            $thumbnailName = 'thumbnail.'.FileTool::removeExtension($uploadedFile->getFilename(), $extension).'jpg';
            $img->fit(135, 80)->save($destinationPath.$thumbnailName);
        }

        return $thumbnailName;
    }
}
