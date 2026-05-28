<?php

namespace App\Libraries;

use Config\Services;

class UploadLibrary
{
    protected $uploadPath;
    protected $allowedTypes;
    protected $maxSize;
    protected $encryptName;

    public function __construct()
    {
        $this->uploadPath = WRITEPATH . 'uploads/';
        $this->allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'];
        $this->maxSize = 5120; // 5MB
        $this->encryptName = true;
    }

    /**
     * Upload single file
     */
    public function uploadFile($fieldName, $subfolder = '')
    {
        $upload = Services::upload();
        
        $config = [
            'upload_path'   => $this->uploadPath . $subfolder,
            'allowed_types' => $this->allowedTypes,
            'max_size'      => $this->maxSize,
            'encrypt_name'  => $this->encryptName,
            'overwrite'     => false
        ];

        // Create directory if not exists
        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, true);
        }

        $upload->initialize($config);

        if (!$upload->doUpload($fieldName)) {
            return [
                'success' => false,
                'error'   => $upload->display_errors(),
                'data'    => null
            ];
        }

        $uploadData = $upload->data();
        
        return [
            'success' => true,
            'error'   => null,
            'data'    => [
                'file_name'      => $uploadData['file_name'],
                'orig_name'      => $uploadData['orig_name'],
                'file_type'      => $uploadData['file_type'],
                'file_path'      => $uploadData['file_path'],
                'full_path'      => $uploadData['full_path'],
                'raw_name'       => $uploadData['raw_name'],
                'client_name'    => $uploadData['client_name'],
                'file_ext'       => $uploadData['file_ext'],
                'file_size'      => $uploadData['file_size'],
                'is_image'       => $uploadData['is_image'],
                'image_width'    => $uploadData['image_width'] ?? null,
                'image_height'   => $uploadData['image_height'] ?? null,
                'image_type'     => $uploadData['image_type'] ?? null,
                'subfolder_path' => $subfolder
            ]
        ];
    }

    public function uploadMultiple($fieldName, $subfolder = '')
    {
        $upload = Services::upload();
        
        $config = [
            'upload_path'   => $this->uploadPath . $subfolder,
            'allowed_types' => $this->allowedTypes,
            'max_size'      => $this->maxSize,
            'encrypt_name'  => $this->encryptName,
            'overwrite'     => false
        ];

        // Create directory if not exists
        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, true);
        }

        $upload->initialize($config);

        $files = $_FILES[$fieldName];
        $uploadResults = [];
        $errors = [];

        // Handle multiple files
        for ($i = 0; $i < count($files['name']); $i++) {
            $_FILES['userfile']['name']     = $files['name'][$i];
            $_FILES['userfile']['type']     = $files['type'][$i];
            $_FILES['userfile']['tmp_name'] = $files['tmp_name'][$i];
            $_FILES['userfile']['error']    = $files['error'][$i];
            $_FILES['userfile']['size']     = $files['size'][$i];

            if (!$upload->doUpload('userfile')) {
                $errors[] = $upload->display_errors();
            } else {
                $uploadData = $upload->data();
                $uploadResults[] = [
                    'file_name'      => $uploadData['file_name'],
                    'orig_name'      => $uploadData['orig_name'],
                    'file_type'      => $uploadData['file_type'],
                    'file_path'      => $uploadData['file_path'],
                    'full_path'      => $uploadData['full_path'],
                    'raw_name'       => $uploadData['raw_name'],
                    'client_name'    => $uploadData['client_name'],
                    'file_ext'       => $uploadData['file_ext'],
                    'file_size'      => $uploadData['file_size'],
                    'is_image'       => $uploadData['is_image'],
                    'image_width'    => $uploadData['image_width'] ?? null,
                    'image_height'   => $uploadData['image_height'] ?? null,
                    'image_type'     => $uploadData['image_type'] ?? null,
                    'subfolder_path' => $subfolder
                ];
            }
        }

        return [
            'success' => empty($errors),
            'errors'  => $errors,
            'data'    => $uploadResults
        ];
    }

    public function resizeImage($sourcePath, $width, $height, $maintainRatio = true)
    {
        $image = Services::image();
        
        try {
            $image->withFile($sourcePath)
                  ->resize($width, $height, $maintainRatio)
                  ->save($sourcePath);
            
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function createThumbnail($sourcePath, $thumbPath, $width = 150, $height = 150)
    {
        $image = Services::image();
        
        try {
            $image->withFile($sourcePath)
                  ->fit($width, $height, 'center')
                  ->save($thumbPath);
            
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function deleteFile($filename, $subfolder = '')
    {
        $filePath = $this->uploadPath . $subfolder . '/' . $filename;
        
        if (file_exists($filePath)) {
            return unlink($filePath);
        }
        
        return false;
    }

    public function getFileUrl($filename, $subfolder = '')
    {
        if (empty($filename)) {
            return base_url('assets/images/default.jpg');
        }
        
        $filePath = 'uploads/' . $subfolder . '/' . $filename;
        
        // Check if file exists in writable/uploads
        if (file_exists(WRITEPATH . $filePath)) {
            return base_url('writable/' . $filePath);
        }
        
        return base_url('assets/images/default.jpg');
    }

    public function isValidFileType($filename)
    {
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        return in_array($ext, $this->allowedTypes);
    }

    public function getFileInfo($filename, $subfolder = '')
    {
        $filePath = $this->uploadPath . $subfolder . '/' . $filename;
        
        if (!file_exists($filePath)) {
            return null;
        }

        return [
            'name' => $filename,
            'size' => filesize($filePath),
            'type' => mime_content_type($filePath),
            'path' => $filePath,
            'url'  => $this->getFileUrl($filename, $subfolder),
            'modified' => date('Y-m-d H:i:s', filemtime($filePath))
        ];
    }

    public function getFilesInDirectory($subfolder = '')
    {
        $directory = $this->uploadPath . $subfolder;
        
        if (!is_dir($directory)) {
            return [];
        }

        $files = [];
        $fileList = scandir($directory);
        
        foreach ($fileList as $file) {
            if ($file !== '.' && $file !== '..' && is_file($directory . '/' . $file)) {
                $files[] = $this->getFileInfo($file, $subfolder);
            }
        }

        return $files;
    }
}