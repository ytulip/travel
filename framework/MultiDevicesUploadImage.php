<?php

namespace MM;

class MultiDevicesUploadImage
{
    public $token;

    /**
     * MultiDevicesUploadImage constructor.
     * @param null $token
     */
    public function __construct($token = null)
    {
        if ($token === null) {
            $token = \MultiDevicesUploadImage::insert([]);
        }
        $this->token = $token;
    }

    public function hasSucceeded()
    {

    }
}
