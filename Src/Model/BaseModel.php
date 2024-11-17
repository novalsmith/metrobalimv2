<?php

namespace App\Src\Model;

class BaseModel
{
    public string $message = "";
    public string $status = "";
    public array $data = [];

    public function getMessage()
    {
        return $this->message;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setMessage(string $value)
    {
        return $this->message = $value;
    }

    public function setStatus(string $value)
    {
        return $this->status = $value;
    }

    public function setData(array $value)
    {
        return $this->data = $value;
    }

}
