<?php

class AutoCompressDelete{

    private $sourceFolder;
    private $compressedFolder;
    function __construct($sourceF,$compressedF){
        $this->sourceFolder = $sourceF;
        $this->compressedFolder = $compressedF;
        $this->compressFile();
    }

    function deleteFileSource($filename){
        unlink($filename);
        return 1;
    }
    function deleteFileCompressedExists($zipPath){
        unlink($zipPath);
        return 1;
    }
    function compressFile(){

        $sourceFolder = $this->sourceFolder.'/';

        // Get all files in the source folder
        $files = glob($sourceFolder . '*');

        foreach ($files as $file) {
            // Create a unique ZIP file name based on the original file's name
            $zipPath =   $this->compressedFolder.'/'. pathinfo($file, PATHINFO_FILENAME) . '.zip';

            // Check if the ZIP file already exists
            if (file_exists($zipPath)) {
                // If it exists, delete the file
               
                $this->deleteFileCompressedExists($zipPath);
            }

            // Create a new ZIP archive for each file
            $zip = new ZipArchive();

            if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {
                // Calculate the relative path inside the ZIP archive
                $relativePath = basename($file);

                // Add the file to the ZIP archive
                $zip->addFile($file, $relativePath);

                $zip->close();

                // Delete the original file from the source folder
               $this->deleteFileSource($file);

                echo 'ok: ' . $zipPath . "<br>";
            } else {
                echo 'failed: ' . $zipPath . "<br>";
            }
        }
    }

}

 #PROCESS: AutoCompressDelete(fileFolder,compressedFolder)
 # or AutoCompressDelete('../../fileFolder','../../compressedFolder')
 $sample = new AutoCompressDelete('sample','compressed');

