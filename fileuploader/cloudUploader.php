<?php
require_once __DIR__.'/UploaderInterface.php';

class CloudUploader implements UploaderInterface{
    public function fileUpload(array $files, string $uploadDir, int $maxSize, array $allowedExt) : array {
        try {
            return [] ;
        } catch (\Throwable $th) {
            echo "Exception: ".$th->getMessage();
        }
    }
}
?>