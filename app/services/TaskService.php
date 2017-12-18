<?php

class TaskService {

    /**
     *
     * @var Task
     */
    public $task;

    private $errors = [];
    
    public function __construct(Task $task) {
        $this->task = $task;
    }

    public function getTask() {
        return $this->task;
    }


    public function bindPartial($data) {
        foreach (['user', 'email', 'description'] as $key) {
            $this->task->$key = $data[$key];
        }
    }
    
    public function isValid($data) {
        $valid = true;

        foreach (['user', 'email', 'description', 'image'] as $key) {
            if (empty($data[$key])) {
                $valid = false;
                $this->errors[] = $key . ' - required';
            }
        }

        $imageData = $this->getImageSizeFromString($data['image']['data']);
        if (!in_array($imageData[2], [IMG_GIF, IMG_JPG, IMG_PNG])) {
            $valid = false;
            $this->errors[] = 'Image type can be only .jpg, .png, .gif!';
        }

        return $valid;
    }

    public function getErrors() {
        return $this->errors;
    }
    
    private function getImageFromBase64($string) {
        list($type, $image) = explode(';', $string);
        list(, $image) = explode(',', $image);
        return base64_decode($image);
    }
    
    private function getImageSizeFromString($string) {
        return getimagesizefromstring($this->getImageFromBase64($string));
    }
    
    public function upload($data) {
        // Set a maximum height and width
        $width = 320;
        $height = 240;

        // Get new dimensions
        $imageData = $this->getImageSizeFromString($data['data']);
        list($widthOrig, $heightOrig) = $imageData;

        $ratioOrig = $widthOrig / $heightOrig;
        if ($width / $height > $ratioOrig) {
            $width = $height * $ratioOrig;
        } else {
            $height = $width / $ratioOrig;
        }

        // Resample
        $imageP = imagecreatetruecolor($width, $height);
        $img = imagecreatefromstring($this->getImageFromBase64($data['data']));
        
        imagecopyresampled($imageP, $img, 0, 0, 0, 0, $width, $height, $widthOrig, $heightOrig);

        
        $dir = '/upload/' . $this->task->id . '/';
        
        $file = $dir . $data['name'];
        
        $fullDir = INC_ROOT . '/' . $dir;
        if (!is_dir($fullDir)) {
            mkdir($fullDir);
        }
        
        $fullPath = INC_ROOT . '/' . $file;
        
        switch ($imageData[2]) {
            case IMG_GIF:
                imagegif($imageP, $fullPath);
                break;
            case IMG_JPG:
                imagejpeg($imageP, $fullPath, 100);
                break;
            case IMG_PNG:
                imagepng($imageP, $fullPath, 100);
                break;
        }
        
        $this->task->image = $data['name'];
    }

}
