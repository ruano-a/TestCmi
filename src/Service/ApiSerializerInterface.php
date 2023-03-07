<?php

namespace App\Service;

interface ApiSerializerInterface
{
    public function serialize($data): array;
}
