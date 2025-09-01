<?php
require_once __DIR__.'/FileUploader.php';

class CloudUploader implements FileUploader{
    public function fileUpload(array $files, string $uploadDir, int $maxSize, array $allowedExt) : array {
        try {
            return [] ;
        } catch (\Throwable $th) {
            echo "Exception: ".$th->getMessage();
        }
    }
}
?>