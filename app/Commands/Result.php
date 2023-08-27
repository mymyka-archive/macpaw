<?php

namespace App\Commands;

/**
 * Dataclass
 * Contains the result of a command
 * Have dynamic properties
 */
class Result
{
    private $data = [];

    public function __construct()
    {
        $this->data['errors'] = [];
        $this->data['infos'] = [];
        $this->data['warnings'] = [];
    }

    public function __get($name) {
        return $this->data[$name];
    }

    public function __set($name, $value) {
        $this->data[$name] = $value;
        return $this;
    }
}