<?php
interface FileUploader{
    public function fileUpload(array $files, string $uploadDir, int $maxSize, array $allowedExt) : array;
}
?>