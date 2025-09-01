<?php
require_once __DIR__.'/UploaderInterface.php';

class LoacalUploader implements UploaderInterface{
    
    public function fileUpload(array $files, string $uploadDir, int $maxSize, array $allowedExt) : array{
        $savedFilenames = [];

        if ($files && isset($files['name'])) {
            foreach ($files['name'] as $key => $name) {
                $tmpName = $files['tmp_name'][$key];
                $size    = $files['size'][$key];
                $error   = $files['error'][$key];

                $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));

                if (in_array($ext, $allowedExt) && $size <= $maxSize && $error === UPLOAD_ERR_OK) {
                    $newName = uniqid() . "." . $ext;
                    $destination = $uploadDir . $newName;

                    if (move_uploaded_file($tmpName, $destination)) {
                        $savedFilenames[] = $newName;
                    }
                }
            }
        }

        return $savedFilenames;
    }
}
?>