<?php

class Task {

    public $id;
    public $user;
    public $email;
    public $description;
    public $image;
    public $done;

    public function __construct($data = []) {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

}
