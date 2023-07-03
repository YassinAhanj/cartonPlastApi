<?php
namespace App\Builder;

class PhotoUploader
{
    private $uploadDir;

    public function __construct($uploadDir)
    {
        $this->uploadDir = $uploadDir;
    }

    public function upload($fileInputName)
    {
        if ($_FILES[$fileInputName]['error'] === UPLOAD_ERR_OK) {
            $tempFilePath = $_FILES[$fileInputName]['tmp_name'];
            $fileName = $_FILES[$fileInputName]['name'];
            $uploadPath = $this->uploadDir . '/' . $fileName;

            if (move_uploaded_file($tempFilePath, $uploadPath)) {
                return $fileName;
            } else {
                throw new \Exception('Failed to move uploaded file.');
            }
        } else {
            throw new \Exception('Error uploading file.');
        }
    }
}
