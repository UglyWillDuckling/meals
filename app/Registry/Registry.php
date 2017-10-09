<?php
namespace App\Registry;

class Registry
{
    protected $data = [];

    public function __get($key)
    {
        if (isset($this->data[$key])) {
            return $this->data[$key];
        }
        return false;
    }

    public function __set($key, $value)
    {
        $this->data[$key] = $value;
    }
}