<?php

namespace App\Service;

interface UrlRequesterInterface
{
    public function get($url): string;
}
