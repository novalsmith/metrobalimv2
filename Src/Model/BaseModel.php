<?php

namespace App\Src\Model;

class BaseModel
{
    public string $message = "";
    public string $status = "";
    public $data = null;

    public function __construct(array $data)
    {
        $this->message = $data['message'] ?? '';
        $this->status = $data['status'] ?? '';
        $this->data = $data['data'] ?? null;

    }

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

}
