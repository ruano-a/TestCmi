<?php

namespace App\Service;

use App\Service\UrlRequesterInterface;

class SimpleUrlRequester implements UrlRequesterInterface
{
    public function get($url): string
    {
        return file_get_contents($url);
    }
}
