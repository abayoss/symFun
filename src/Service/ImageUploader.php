<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageUploader
{
    private $targetDirectory;

    public function __construct($targetDirectory, $targetDirectoryUrl)
    {
        $this->targetDirectory = $targetDirectory;
        $this->targetDirectoryUrl = $targetDirectoryUrl;
    }

    public function upload(UploadedFile $file)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $fileName = $originalFilename . '-' . uniqid() . '.' . $file->guessExtension();
        
        try {
            $file->move($this->getTargetDirectory() . $this->getTargetDirectoryUrl(), $fileName);
        } catch (FileException $e) {
            return var_dump($e);
        }

        return $this->getTargetDirectoryUrl() . $fileName;
    }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }
    public function getTargetDirectoryUrl()
    {
        return $this->targetDirectoryUrl;
    }
}
