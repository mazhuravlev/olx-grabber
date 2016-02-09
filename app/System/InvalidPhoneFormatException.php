<?php

namespace App\System;


class InvalidPhoneFormatException extends \Exception
{

    private $phone;

    public function setPhone($phone)
    {
        $this->phone = $phone;
        return $this;
    }

    public function getPhone()
    {
        return $this->phone;
    }

}